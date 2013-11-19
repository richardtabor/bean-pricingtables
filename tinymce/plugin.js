// closure to avoid namespace collision
(function () {
	// create the plugin
	tinymce.create("tinymce.plugins.BeanPricingTables", {

		//CREATES CONTROL INSTANCES BASED ON CONTROL'S ID - OUR BUTTON ID IS bean_pricingtables_button
		createControl: function ( btn, e ) {console.log(btn);
			if ( btn == "bean_pricingtable_button" ) {

				var a = this;

				//CREATE THE BUTTON
				var btn = e.createSplitButton('bean_pricingtable_button', {
                    title: "Insert Bean Pricing Table",
                });

				//RENDER A DROPDOWN MENU
                btn.onRenderMenu.add(function (c, b) {
					a.addImmediate( b, "Pricing Table, 1 Column",
					'[pricing_table columns="1"][pricing_column highlight="true"][price_info title="Free" cost="Zero dollars." url="http://themebeans.com" highlighted="Most Popular"]This is a quick description or something of the free package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>32GB</span> of Free Space</li><li>Completely Editable</li><li>Mobile Ready</li></ul>[/pricing_column][/pricing_table]'
					);
					a.addImmediate( b, "Pricing Table, 2 Column",
					'[pricing_table columns="2"][pricing_column][price_info title="Free" cost="Zero dollars." url="http://themebeans.com" highlighted="Most Popular"]This is a quick description or something of the free package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>32GB</span> of Free Space</li><li>Completely Editable</li><li>Mobile Ready</li></ul>[/pricing_column][pricing_column][price_info title="Basic" cost="$5.99 / month." url="http://themebeans.com"]This is a quick description or something of the basic package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>64GB</span> of Free Space</li><li>Completely Editable</li><li>Loads more Features</li></ul>[/pricing_column][/pricing_table]'
					);
					a.addImmediate( b, "Pricing Table, 3 Column",
					'[pricing_table columns="3"][pricing_column][price_info title="Free" cost="Zero dollars." url="http://themebeans.com"]This is a quick description of the free package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>32GB</span> of Free Space</li><li>Completely Editable</li><li>Mobile Ready</li></ul>[/pricing_column][pricing_column][price_info title="Basic" cost="$5.99 / month." url="http://themebeans.com" highlighted="Most Popular"]This is a quick description of the basic package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>64GB</span> of Free Space</li><li>Completely Editable</li><li>Loads more Features</li></ul>[/pricing_column][pricing_column][price_info title="Premium" cost="$19.99 / month" url="http://themebeans.com"]This is a quick description of the premium package.[/price_info]<ul><li class="info" title="An incredibly intuitive pricing tables plugin. Generated via shortcodes so its nice and easy.">Bean Pricing Tables</li><li class="info" title="This is an additional info hover. Simply add the info class, and a title to your list item.">List Hover Example</li><li class="info" title="Just surround your text in a span HTML element to highlight elements"><span>128GB</span> of Free Space</li><li>Completely Editable</li><li>Loads more Features</li></ul>[/pricing_column][/pricing_table]'
					);


				});

                return btn;
			}

			return null;
		},

		//INSERT SHORTCODE AS DIRECT CONTENT
		addImmediate: function ( ed, title, sc) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
				}
			})
		},

		//CREDS
		getInfo: function () {
			return {
				longname : "ThemeBeans Shortcodes",
				author : 'ThemeBeans',
				authorurl : 'http://themebeans.com/',
				infourl : 'http://themebeans.com/plugin/bean-shortcodes-plugin',
				version : "1.0"
			};
		}
	});

	//ADD PRICING TABLE PLUGIN
	tinymce.PluginManager.add("BeanPricingTables", tinymce.plugins.BeanPricingTables);
})();