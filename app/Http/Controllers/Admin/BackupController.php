<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends AdminController
{
    /**
     * Export and download full database SQL backup.
     *
     * @return StreamedResponse
     */
    public function downloadDbBackup(): StreamedResponse
    {
        $databaseName = config('database.connections.mysql.database') ?: 'sohoj_rms';
        $filename = 'sohoj_rms_db_backup_' . date('Y_m_d_His') . '.sql';

        return response()->streamDownload(function () use ($databaseName) {
            $handle = fopen('php://output', 'w');
            $pdo = DB::getPdo();

            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Sohoj RMS Database Backup\n");
            fwrite($handle, "-- Database: `{$databaseName}`\n");
            fwrite($handle, "-- Date / Time: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET AUTOCOMMIT = 0;\n");
            fwrite($handle, "START TRANSACTION;\n");
            fwrite($handle, "SET time_zone = \"+00:00\";\n\n");

            $tables = DB::select('SHOW TABLES');
            $dbKey = 'Tables_in_' . $databaseName;

            foreach ($tables as $t) {
                $tableName = $t->$dbKey ?? current((array) $t);

                // DDL: Structure
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for table `{$tableName}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                $createTableRes = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTableRes)) {
                    $createRow = (array) $createTableRes[0];
                    $createTableSql = $createRow['Create Table'] ?? current($createRow);
                    fwrite($handle, $createTableSql . ";\n\n");
                }

                // DML: Data
                $count = DB::table($tableName)->count();
                if ($count > 0) {
                    fwrite($handle, "-- Dumping data for table `{$tableName}` ({$count} rows)\n");

                    DB::table($tableName)->orderBy(DB::raw('1'))->chunk(100, function ($rows) use ($handle, $tableName, $pdo) {
                        $inserts = [];
                        foreach ($rows as $row) {
                            $values = [];
                            foreach ((array) $row as $val) {
                                if (is_null($val)) {
                                    $values[] = 'NULL';
                                } elseif (is_int($val) || is_float($val)) {
                                    $values[] = $val;
                                } else {
                                    $values[] = $pdo->quote((string) $val);
                                }
                            }
                            $inserts[] = '(' . implode(', ', $values) . ')';
                        }
                        if (!empty($inserts)) {
                            fwrite($handle, "INSERT INTO `{$tableName}` VALUES \n" . implode(",\n", $inserts) . ";\n");
                        }
                    });
                    fwrite($handle, "\n");
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($handle, "COMMIT;\n");
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
