// closure to avoid namespace collision
(function () {
    //ADD PRICING TABLE PLUGIN
    tinymce.PluginManager.add("BeanPricingTables", function(editor, url) {
        //CREATE THE BUTTON
        editor.addButton('bean_pricingtable_button', {
            type: "splitbutton",
            title: "Insert Bean Pricing Table", //BUTTON TITLE
            menu: [
                 createSubmenuButtonImmediate( "Pricing Table, 1 Column",
                    '[pricing_table columns="1"][pricing_column highlight="true"][price_info title="Free" cost="Zero dollars." url="http://themebeans.com" highlighted="Most Popular"]This is a quick description or something of the free package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>32GB</span> of Free Space</li><li>Completely Editable</li><li>Mobile Ready</li></ul>[/pricing_column][/pricing_table]' 
                    ),
                 createSubmenuButtonImmediate( "Pricing Table, 2 Column",
                    '[pricing_table columns="2"][pricing_column][price_info title="Free" cost="Zero dollars." url="http://themebeans.com" highlighted="Most Popular"]This is a quick description or something of the free package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>32GB</span> of Free Space</li><li>Completely Editable</li><li>Mobile Ready</li></ul>[/pricing_column][pricing_column][price_info title="Basic" cost="$5.99 / month." url="http://themebeans.com"]This is a quick description or something of the basic package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>64GB</span> of Free Space</li><li>Completely Editable</li><li>Loads more Features</li></ul>[/pricing_column][/pricing_table]' 
                    ),
                 createSubmenuButtonImmediate( "Pricing Table, 3 Column",
                    '[pricing_table columns="3"][pricing_column][price_info title="Free" cost="Zero dollars." url="http://themebeans.com"]This is a quick description of the free package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>32GB</span> of Free Space</li><li>Completely Editable</li><li>Mobile Ready</li></ul>[/pricing_column][pricing_column][price_info title="Basic" cost="$5.99 / month." url="http://themebeans.com" highlighted="Most Popular"]This is a quick description of the basic package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>64GB</span> of Free Space</li><li>Completely Editable</li><li>Loads more Features</li></ul>[/pricing_column][pricing_column][price_info title="Premium" cost="$19.99 / month" url="http://themebeans.com"]This is a quick description of the premium package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>128GB</span> of Free Space</li><li>Completely Editable</li><li>Loads more Features</li></ul>[/pricing_column][/pricing_table]'
                     )
            ],
            onclick: function() {}
        });

        function createSubmenuButtonImmediate( title, sc ) {
            return {
                text: title,
                onclick: function() {
                    executeTinyMCECommand( 'mceInsertContent', sc );
                }
            }
        }

        function executeTinyMCECommand( command, args ) {
            if (typeof window.tinyMCE.activeEditor != 'undefined') {
                window.tinyMCE.activeEditor.selection.moveToBookmark(window.tinymce_cursor);
            }
            if (typeof window.tinyMCE.execInstanceCommand != 'undefined') {
                window.tinyMCE.execInstanceCommand('content', command, false, args);

            } else {
                if (typeof window.tinyMCE.execCommand != 'undefined') {
                    window.tinyMCE.get('content').execCommand(command, false, args);
                }
            }
        }
	});
})();