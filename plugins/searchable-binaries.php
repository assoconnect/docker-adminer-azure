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
	var inputs = document.querySelectorAll('div#fieldset-search > div > input');
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].addEventListener('change', function(event) {
		    if (event.target.value.match(/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/)) {
		        event.target.value = uuidToHex(event.target.value);
		    }
		    if (event.target.value.match(/^[0-9A-HJ-KMNP-TV-Z]{26}$/)) {
		        event.target.value = base32ToHex(event.target.value);
		    }
		})
	}
});

EOT;
    }

    function head() {
        echo script($this->prepend);
    }
}
