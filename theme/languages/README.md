# SOP: Handling Translations with PO/POT/MO Files

This SOP document guides you through handling translations using PO, POT, and MO files. It is intended for developers, translators, and anyone involved in the translation process.

## 1. Understanding Translations with PO/POT/MO Files in WordPress

WordPress uses a system called "gettext" for translations. Gettext is a powerful and widely used internationalization (i18n) and localization (l10n) system in open-source projects, including WordPress. It uses PO, POT, and MO files for managing translations.

1. **POT files (Portable Object Template)**: These files act as templates containing all the text strings (words, sentences) that are used in WordPress plugins and themes. These files are used to generate PO files in various languages.
1. **PO files (Portable Object)**: These files contain the translations. Each language will have its own PO file. These files are editable and are used to write the translations.
1. **MO files (Machine Object)**: Once the PO file is complete, it is compiled into an MO file. These are binary files that WordPress can read quickly, providing the translated strings to the end-users.

WordPress uses the __() and _e() PHP functions to retrieve and echo (respectively) the translated strings from the MO files. When a plugin, a theme, or WordPress itself is ready to be translated, a POT file is generated, translators fill in translations in a PO file, and then the MO file is generated from the PO file.

Each WordPress theme or plugin should contain a 'languages' directory where you store all the necessary POT, PO, and MO files. WordPress uses the language setting of the website to choose which language file (MO file) to load.

Please note that you should never edit an MO file directly. Instead, use PoEdit or a similar tool to open and edit the corresponding PO file, then save your changes to generate a new MO file.

## 2. Software Used for Translations

We use PoEdit, a cross-platform software for editing PO and POT files. It is user-friendly and simplifies the translation process. Visit [PoEdit](https://poedit.net/) for installation instructions.

## 3. Our Workflow

The POT file is our absolute source of truth. To ensure consistency, always refresh PO files from POT files, not directly from the source code.

### Step 1: Generate or Refresh the POT File

The POT file contains the original strings for translation. Run the terminal command `composer run i18n-make-pot` to create or update the POT file.

### Step 2: Generate or Refresh a PO File from the POT File

The PO file contains the translations. Generate or refresh a PO file in the desired language from the POT file. Remember to use PoEdit for this step.

### Step 3: Translate as Desired

Translate the strings in the PO file using PoEdit. Always save your work to avoid losing any translations.

### Step 4: Generate Translation-json Files

The translation-json files store the translated strings. Generate these files by running the terminal command `composer run i18n-make-json`.

## 4. Troubleshooting

If you encounter any issues during the translation process, try restarting the software and repeating the process. If the problem persists, reach out to your supervisor or team leader.
