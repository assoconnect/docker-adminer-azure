<?php

/** This plugin replaces UNIX timestamps with human-readable dates in your local format.
 * Mouse click on the date field reveals timestamp back.
 *
 * @link https://www.adminer.org/plugins/#use
 * @author Anonymous
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class AdminerReadableBinaries {
    /** @access protected */
    var $prepend;

    function __construct() {
        $this->prepend = <<<EOT
function hexToBase32(hex) {
    const base32Chars = "0123456789ABCDEFGHJKMNPQRSTVWXYZ";
    let value = BigInt('0x' + hex); // Convert the hex string to BigInt
    let ulid = '';

    // Continuously divide the value by 32 and get the remainders
    while (value > 0) {
        const remainder = value % BigInt(32);
        ulid = base32Chars[Number(remainder)] + ulid;
        value = value / BigInt(32);
    }

    // Pad the result to 26 characters, as ULID should always be 26 chars long
    return ulid.padStart(26, '0');
}
function hexToUuid(hex) {
    // Correctly map the hexadecimal to UUID format
    const rearranged = hex.slice(8, 16) +
                       '-' + hex.slice(4, 8) +
                       '-' + hex.slice(0, 4) +
                       '-' + hex.slice(16, 20) +
                       '-' + hex.slice(20);

    // Return the lowercased UUID string
    return rearranged.toLowerCase();
}

function createElementFromHTML(htmlString) {
  var div = document.createElement('div');
  div.innerHTML = htmlString.trim();

  // Change this to div.childNodes to support multiple top-level nodes.
  return div.firstChild;
}

document.addEventListener('DOMContentLoaded', function(event) {
    var nextType = {'ulid': 'uuid', 'uuid': 'raw', 'raw': 'ulid'};
    var tds = document.querySelectorAll('td[id^="val"]');
    for (var i = 0; i < tds.length; i++) {
        var text = tds[i].innerText.trim();
        if (text.match(/^[0-9A-F]{32}$/)) {
            var flexContainer = document.createElement('div');
            flexContainer.style.display = 'flex';
            flexContainer.style['justify-content'] = 'space-between';

            flexContainer.raw = '<code title="Raw">' + text + '</code>';
            flexContainer.ulid = '<code title="ULID">' + hexToBase32(text) + '</code>';
            flexContainer.uuid = '<code title="UUID">' + hexToUuid(text) + '</code>';
            
            var child = tds[i].childNodes[0];
            flexContainer.appendChild(child);
            tds[i].appendChild(flexContainer);
            var node = flexContainer.childNodes[0].nodeName == 'A' ? flexContainer.childNodes[0] : flexContainer;
            node.innerHTML = flexContainer.ulid;
            flexContainer.type = 'ulid';
            
            var switchButton = document.createElement('span');
            switchButton.innerText = ' 🔄  ';
            switchButton.style.cursor = 'pointer';
            flexContainer.prepend(switchButton);

            flexContainer.childNodes[0].addEventListener('click', function(event) {
                this.parentElement.type = nextType[this.parentElement.type];
                if (this.parentElement.childNodes[1].nodeName == 'A') {
                    var node = this.parentElement.childNodes[1];
                    node.replaceChild(createElementFromHTML(this.parentElement[this.parentElement.type]), node.childNodes[0]);
                } else {
                    var node = this.parentElement;
                    node.replaceChild(createElementFromHTML(this.parentElement[this.parentElement.type]), node.childNodes[1]);
                }
            });
        }
    }
});

EOT;
    }

    function head() {
        echo Adminer\script($this->prepend);
    }
}
