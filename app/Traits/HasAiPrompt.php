<?php

namespace App\Traits;


use App\Models\Language;
use Dipokhalder\Settings\Facades\Settings;

trait HasAiPrompt
{

    public string $langName = 'English';

    public string $langCode = 'EN';

    public function loadDefaultLanguage(): void
    {
        $defaultLanguage = Settings::group('site')->get('site_default_language');
        $language = Language::find($defaultLanguage);
        if ($language) {
            $this->langName = $language->name;
            $this->langCode = strtoupper($language->code);
        }
    }

    public function buildProductNamePrompt(string $name): string
    {
        return <<<PROMPT
          You are a professional food menu copywriter for a food delivery platform.

          Rewrite the food item name "{$name}" as a creative, appetizing, and concise menu item title.

          CRITICAL INSTRUCTION:
          - The output must be 100% in language "{$this->langName}" (Code: {$this->langCode}) — this is mandatory.
          - If the original name is not in "{$this->langName}", fully translate it into "{$this->langName}" while keeping the appetizing meaning.
          - Do not mix languages; use only "{$this->langName}" characters and words.
          - Keep it short (3-8 words), appetizing, and ready for a restaurant menu.
          - No extra words, slogans, or punctuation like quotes.
          - Return only the translated title as plain text in "{$this->langName}".

      IMPORTANT:
        - Only process inputs that are actual food items, beverages, or restaurant meals.
        - If the input is electronics, clothing, gadgets, or anything unrelated to food, respond with only "INVALID_INPUT".
        - If the original input is not meaningful or cannot be converted into a food menu title, respond with only "INVALID_INPUT".
        - Do not return generic explanations, fallback messages, or translations for unrelated items.
      PROMPT;
    }

    public function buildProductDescriptionPrompt(string $description): string
    {
        return <<<PROMPT
        You are a creative and professional food menu copywriter for a food delivery platform.

        Generate a detailed, engaging, and persuasive description for the food item named "{$description}".

        CRITICAL LANGUAGE RULES:
        - The entire description must be written 100% in {$this->langName} (Code: {$this->langCode}) — this is mandatory.
        - If the food name is in another language, translate and localize it naturally into {$this->langName}.
        - Do not mix languages; use only {$this->langName} characters and words.
        - Adapt the tone, phrasing, and examples to be natural for {$this->langName} readers.

        Content & Structure:
        - Start with a short introductory paragraph describing the taste, aroma, and main appeal of the dish.
        - Follow with a "Key Highlights:" section (translated to {$this->langName}).
        - Each highlight should be on a new line starting with a CAPITALIZED feature title (e.g., FLAVOR, TEXTURE) followed by a colon and the description.
        - Follow with an "Ingredients & Details:" section (translated to {$this->langName}).
        - List key ingredients, serving size, or dietary info (e.g., Spicy, Vegan) as bullet points starting with "- ".
        - Keep text appetizing, concise, and ready for a menu.
        - End with a closing sentence highlighting why this dish is a must-try.

        Formatting:
        - Output MUST BE PLAIN TEXT ONLY.
        - Do NOT include any HTML tags like <p>, <b>, <h1>, <li>, etc.
        - Do NOT include any markdown syntax, code fences, or triple backticks.
        - Use standard line breaks to separate sections and paragraphs.
        - Return only the description content without any commentary.

         IMPORTANT:
        - Only process inputs that are actual food items, beverages, or restaurant meals.
        - If the input is electronics, clothing, gadgets, or anything unrelated to food, respond with only "INVALID_INPUT".
        - If the original input is not meaningful or cannot be converted into a food menu description, respond with only "INVALID_INPUT".
        - Do not return generic explanations, fallback messages, or translations for unrelated items.
        PROMPT;
    }
    public function buildProductCautionPrompt(string $name): string
    {
        return <<<PROMPT
        You are a creative and professional food menu copywriter for a food delivery platform.

        Generate a short, clear, and customer-safe caution note for the food item named "{$name}".

        CRITICAL LANGUAGE RULES:
        
    The entire caution note must be written 100% in {$this->langName} (Code: {$this->langCode}) — this is mandatory.
    If the food name is in another language, translate and localize it naturally into {$this->langName}.
    Do not mix languages; use only {$this->langName} characters and words.
    Adapt the tone, phrasing, and examples to be natural for {$this->langName} readers.

            Content & Structure:
            
    Write a concise warning (1-2 sentences) about allergens, spice level, dietary restrictions, or serving notes.
    Mention any relevant caution points such as dairy, gluten, nuts, shellfish, spiciness, or temperature.
    Keep it helpful, polite, and suitable for a food menu or order note.
    Do not start with or repeat the item name "{$name}".

            Formatting:
            
    Output plain text only, no HTML tags, markdown, or special formatting.
    Use natural sentence structure with proper punctuation.
    Keep it brief and direct.
    Return only the plain text caution note without any commentary.

            IMPORTANT:
            
    Only process inputs that are actual food items, beverages, or restaurant meals.
    If the input is electronics, clothing, gadgets, or anything unrelated to food, respond with only "INVALID_INPUT".
    If the original input is not meaningful or cannot be converted into a food menu caution note, respond with only "INVALID_INPUT".
    Do not return generic explanations, fallback messages, or translations for unrelated items.
PROMPT;
    }
}
