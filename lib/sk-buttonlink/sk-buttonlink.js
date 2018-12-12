(function($) {

	tinymce.create('tinymce.plugins.ButtonLink', {
		init : function(ed, url) {
			ed.addButton( 'sk_buttonlink', {
				title : 'Knapplänk',
				image : msvaTemplateDir + '/assets/images/admin/buttonlink.png',
				onclick : function() {

					ed.windowManager.open({
                        title: "Knapplänk",
						body: [
							  {
                    type: 'textbox',
										label: 'Länktext',
										name: 'title'
				},

				{
                    type: 'textbox',
										label: 'Länk/url',
										name: 'link'
				},
				
						],
						onSubmit: function(e) {
							var selected = tinyMCE.activeEditor.selection.getContent();

							var link = this.toJSON().link;
							var title = this.toJSON().title;

							var content =  '<p>';
							    content += '[knapplank title="'+title+'" link="'+link+'"]';
							    content += '</p>';

							ed.insertContent(content);

						}
					});

				}

			});
		},
		createControl : function(n, cm) {
			return null;
		},
	});

	tinymce.PluginManager.add( 'sk_buttonlink', tinymce.plugins.ButtonLink );

})(jQuery);