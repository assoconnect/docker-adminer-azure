<?php

/** This plugin replaces UNIX timestamps with human-readable dates in your local format.
 * Mouse click on the date field reveals timestamp back.
 *
 * @link https://www.adminer.org/plugins/#use
 * @author Anonymous
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class AdminerSearchableBinaries {
    /** @access protected */
    var $prepend;

    private $allowedBinaryColumns = [];

    function __construct() {
        $this->prepend = <<<EOT
function base32ToHex(ulid) {
    const base32Chars = "0123456789ABCDEFGHJKMNPQRSTVWXYZ";
    let value = BigInt(0);
    
    // Decode each Base32 character to its corresponding BigInt value
    for (const char of ulid) {
        const idx = base32Chars.indexOf(char.toUpperCase());
        if (idx === -1) {
            throw new Error('Invalid character in ULID: ' + char);
        }
        value = (value * BigInt(32)) + BigInt(idx);
    }

    // Convert the BigInt value to a hexadecimal string, padded to 32 characters
    let hex = value.toString(16).toUpperCase().padStart(32, '0');

    return hex;
}

function uuidToHex(uuid) {
    // Remove dashes from the UUID
    const noDashes = uuid.replace(/-/g, '');

    // Rearrange the sections of the UUID into the required order
    const rearranged = noDashes.slice(12, 16) +  // Characters 12-15
                       noDashes.slice(8, 12) +   // Characters 8-11
                       noDashes.slice(0, 8) +    // Characters 0-7
                       noDashes.slice(16);       // Characters 16 onwards

    // Return the uppercased hex value
    return rearranged.toUpperCase();
}

document.addEventListener('DOMContentLoaded', function(event) {
    var form = document.querySelector('form#form');
	var inputs = document.querySelectorAll('div#fieldset-search > div > input');
    form.addEventListener('submit', function(event) {
        for (var i = 0; i < inputs.length; i++) {
            var colName = document.querySelector('[name="' + inputs[i].name.slice(0, -5) + '[col]"]').value;
            if (colName && window.allowedBinaryColumns && !window.allowedBinaryColumns.includes(colName)) {
                continue;
            }

            if (inputs[i].value.match(/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/)) {
                inputs[i].value = uuidToHex(inputs[i].value);
            }
            if (inputs[i].value.match(/^[0-9A-HJ-KMNP-TV-Z]{26}$/)) {
                inputs[i].value = base32ToHex(inputs[i].value);
            }
        }
    });
});

EOT;
    }

    /**
     * Hook to create a whitelist based on column type (BINARY) during we display a table
     */
    function tableName($table) {
        $fields = Adminer\fields($table['Name']);

        $this->allowedBinaryColumns = [];
        foreach ($fields as $name => $field) {
            if ($field['type'] === 'binary') {
                $this->allowedBinaryColumns[] = $name;
            }
        }
    }

    /** Print search box in select
     * @param list<string> $where result of selectSearchProcess()
     * @param string[] $columns selectable columns
     * @param Index[] $indexes
     */
    function selectSearchPrint(array $where, array $columns, array $indexes)
    {
        ?>
        <script<?php echo Adminer\nonce(); ?>>
            window.allowedBinaryColumns = <?php echo json_encode($this->allowedBinaryColumns); ?>;
        </script>
        <?php
    }

    function head() {
        echo Adminer\script($this->prepend);
    }
}
