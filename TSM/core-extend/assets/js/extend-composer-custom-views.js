(function($) {
	"use strict";

    window.VcIconView = vc.shortcode_view.extend({
        changeShortcodeParams: function (model) {
            var params = model.get('params'),
                settings = vc.map[model.get('shortcode')],
                inverted_value;
            if (_.isArray(settings.params)) {
                _.each(settings.params, function (p) {
                    if (!_.isUndefined(p.admin_label) && p.admin_label) {
                        var name = p.param_name,
                            value = params[name],
                            $wrapper = this.$el.find('> .wpb_element_wrapper'),
                            $admin_label = $wrapper.children('.admin_label_' + name);

                        if ($admin_label.length) {
                            if (value === '' || _.isUndefined(value)) {
                                $admin_label.hide().addClass('hidden-label');
                            } else {
                                if (name == 'icon_type') {
                                    // Get icon class to display
                                    if (!_.isUndefined(params["icon_" + value])) {
                                        value = vc_toTitleCase(value) + ' - ' + "<i class='" + params["icon_" + value] + "'></i>";
                                    }
                                }
                                $admin_label.html('<label>' + $admin_label.find('label').text() + '</label>: ' + value);
                                $admin_label.show().removeClass('hidden-label');
                            }
                        }
                    }
                }, this);
            }
            var view = vc.app.views[this.model.get('parent_id')];
            if (this.model.get('parent_id') !== false && _.isObject(view)) {
                view.checkIsEmpty();
            }
        }
    });
	

	window.MNKYPostView = vc.shortcode_view.extend( {
		changeShortcodeParams: function ( model ) {
			var params;

			window.MNKYPostView.__super__.changeShortcodeParams.call( this, model );
			params = model.get( 'params' );
			if ( _.isObject( params ) ) {
				if( ! this.$el.find( '> .wpb_element_wrapper .taxonomy').hasClass('mgp_wrapped') ){
					this.$el.find( '> .wpb_element_wrapper .taxonomy' ).addClass('mgp_wrapped').wrapAll( '<div class="mgp_params_wrapper" />');
				}
						
				if ( params.taxonomy == 'all_posts' ) {
					this.$el.find( '> .wpb_element_wrapper .taxonomy' ).html('<span class="taxonomy-all">Show all posts</span>');
					this.$el.find( '> .wpb_element_wrapper .admin_label_author' ).hide();
				} else if ( params.taxonomy == 'author' ) {
					this.$el.find( '> .wpb_element_wrapper .admin_label_author' ).show().addClass('taxonomy-all');
					this.$el.find( '> .wpb_element_wrapper .admin_label_author label' ).text('Posts by');
					this.$el.find( '> .wpb_element_wrapper .taxonomy' ).html('');
				} else {
					this.$el.find( '> .wpb_element_wrapper .admin_label_author' ).hide();
					
					if ( params.taxonomy == 'category' ) {
						if ( params.category != '' ) {
							var list = params.category;
							var listAsArray = list.split(', ');
							var newListHtml = '';
							for(var i=0; i<listAsArray.length; i++) {
								newListHtml += '<span class="selected-taxonomy '+ params.tax_operator.replace(/ /g, '_') +'">';
								newListHtml += listAsArray[i];
								newListHtml += '</span>';
							}
							var finalOutput = newListHtml;
						} else {
							var finalOutput = '<span class="taxonomy-all">No category selected.</span>';
						}						
					} else if( params.taxonomy == 'post_tag' ){					
						if ( params.tag != '' ) {
							var list = params.tag;
							var listAsArray = list.split(', ');
							var newListHtml = '';
							for(var i=0; i<listAsArray.length; i++) {
								newListHtml += '<span class="selected-taxonomy '+ params.tax_operator.replace(/ /g, '_') +'">';
								newListHtml += listAsArray[i];
								newListHtml += '</span>';
							}
							var finalOutput = newListHtml;
						} else {
							var finalOutput = '<span class="taxonomy-all">No tag selected.</span>';
						}			
					} 
					
					if ( params.taxonomy_2 == 'category' ) {
						if ( params.category_2 != '' ) {
							var list = params.category_2;
							var listAsArray = list.split(', ');
							var newListHtml = '';
							for(var i=0; i<listAsArray.length; i++) {
								newListHtml += '<span class="selected-taxonomy '+ params.tax_operator_2.replace(/ /g, '_') +'">';
								newListHtml += listAsArray[i];
								newListHtml += '</span>';
							}
							var finalOutput_2 = '<span class="tax-relation">' + params.tax_relation + '</span>' + newListHtml;
						} else {
							var finalOutput_2 = '<span class="taxonomy-all">No category selected.</span>';
						}						
					} else if( params.taxonomy_2 == 'post_tag' ){			
						if ( params.tag_2 != '' ) {
							var list = params.tag_2;
							var listAsArray = list.split(', ');
							var newListHtml = '';
							for(var i=0; i<listAsArray.length; i++) {
								newListHtml += '<span class="selected-taxonomy '+ params.tax_operator_2.replace(/ /g, '_') +'">';
								newListHtml += listAsArray[i];
								newListHtml += '</span>';
							}
							var finalOutput_2 = '<span class="tax-relation">' + params.tax_relation + '</span>' + newListHtml;
						} else {
							var finalOutput_2 = '<span class="taxonomy-all">No tag selected.</span>';
						}			
					} else {
						var finalOutput_2 = '';
					}
					
					this.$el.find( '> .wpb_element_wrapper .taxonomy' ).html( finalOutput + finalOutput_2);
					
				} 
			}
		}
	} );	

	
	window.VcTestimonialsView = vc.shortcode_view.extend({
        adding_new_tab:false,
        events:{
            'click .add_tab':'addTab',
            'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete':'deleteShortcode',
            'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit':'editElement',
            'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone':'clone'
        },
        render:function () {
            window.VcTestimonialsView.__super__.render.call(this);
            this.$content.sortable({
                axis:"y",
                handle:".wpb_element_wrapper,.vc_element-move",
                stop:function (event, ui) {
                    // IE doesn't register the blur when sorting
                    // so trigger focusout handlers to remove .ui-state-focus
                    ui.item.prev().triggerHandler("focusout");
                    $(this).find('> .wpb_sortable').each(function () {
                        var shortcode = $(this).data('model');
                        shortcode.save({'order':$(this).index()}); // Optimize
                    });
                }
            });
            return this;
        },
        changeShortcodeParams:function (model) {
            window.VcTestimonialsView.__super__.changeShortcodeParams.call(this, model);
            var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
            if (this.$content.hasClass('ui-accordion')) {
                this.$content.accordion("option", "collapsible", collapsible);
            }
        },
        changedContent:function (view) {
            if (this.$content.hasClass('ui-accordion')) this.$content.accordion('destroy');
            var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
            this.$content.accordion({
                header:"h3",
                navigation:false,
                autoHeight:true,
                heightStyle: "content",
                collapsible:collapsible,
                active:this.adding_new_tab === false && view.model.get('cloned') !== true ? 0 : view.$el.index()
            });
            this.adding_new_tab = false;
        },
        addTab:function (e) {
            this.adding_new_tab = true;
            e.preventDefault();
            vc.shortcodes.create({shortcode:'mnky_testimonial', params:{name:'New Testimonial', content:'Click edit button to change this text.'}, parent_id:this.model.id});
        },
        _loadDefaults:function () {
            window.VcTestimonialsView.__super__._loadDefaults.call(this);
        }
    });
	
		
	window.VcPricingView = window.VcColumnView.extend({
        events:{
          'click > .vc_controls .vc_control-btn-delete':'deleteShortcode',
          'click > .vc_controls .vc_control-btn-prepend':'addElement',
          'click > .vc_controls .vc_control-btn-edit':'editElement',
          'click > .vc_controls .vc_control-btn-clone':'clone',
          'click > .wpb_element_wrapper > .vc_empty-container':'addToEmpty'
        },
		changeShortcodeParams:function (model) {
            var params = model.get('params');
            window.VcPricingView.__super__.changeShortcodeParams.call(this, model);
            if (_.isObject(params)) {
				this.$el.find( '.wpb_column_container' ).before( this.$el.find( 'h4.title' ) );
				this.$el.find( '.wpb_column_container' ).before( this.$el.find( 'span.wpb_vc_param_value' ) );
            }			
        }
    });

	

})(window.jQuery);