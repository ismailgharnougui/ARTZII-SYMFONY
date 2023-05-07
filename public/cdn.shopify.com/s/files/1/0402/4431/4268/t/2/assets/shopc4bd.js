jQuery(function($){

	// SEARCH FORMS =============================================================================================================
	$(document).on('submit', '.search_form', function(e) {
		var searchQuery = $(this).find('input').val().replace(/ /g, '+');
		var placeHolder = $(this).find('input').attr('placeholder').replace(/ /g, '+');

		if ( !(searchQuery.length && searchQuery != placeHolder) ) {
			e.preventDefault();
			e.stopPropagation();
		};
	});




	// PRODUCT QUANTITY BOX =====================================================================================================
	$(document).on("focusout",".quantity_input",function(){var a=$(this).val();isNaN(parseFloat(a))&&!isFinite(a)||0==parseInt(a)||""==a?$(this).val(1):parseInt(a)<0?$(this).val(parseInt(a)-2*parseInt(a)):$(this).val(parseInt(a))}),$(document).on("click",".quantity_up",function(){var a=$(this).parent().find(".quantity_input");isNaN(parseFloat(a.val()))||!isFinite(a.val())||a.attr("disabled")?a.val(1):a.val(parseInt(a.val())+1)}),$(document).on("click",".quantity_down",function(){var a=$(this).parent().find(".quantity_input");!isNaN(parseFloat(a.val()))&&isFinite(a.val())&&a.val()>1&&!a.attr("disabled")?a.val(parseInt(a.val())-1):a.val(1)});

	// $(document).on('focusout', '.quantity_input', function() {
	// 	var N = $(this).val();

	// 	if ( ( isNaN(parseFloat( N )) && !isFinite( N ) ) || parseInt( N ) == 0 || N == '' ) {
	// 		$(this).val(1);
	// 	}
	// 	else if ( parseInt( N ) < 0 ) {
	// 		$(this).val( parseInt( N ) - parseInt( N )*2 );
	// 	}
	// 	else {
	// 		$(this).val( parseInt( N ) );
	// 	};
	// });

	// $(document).on('click', '.quantity_up', function() {
	// 	var N = $(this).parent().find('.quantity_input');

	// 	if ( !isNaN( parseFloat( N.val() ) ) && isFinite( N.val() ) && !N.attr('disabled') ) {
	// 		N.val( parseInt( N.val() ) + 1 );
	// 	}
	// 	else {
	// 		N.val(1);
	// 	};
	// });

	// $(document).on('click', '.quantity_down', function() {
	// 	var N = $(this).parent().find('.quantity_input');

	// 	if ( !isNaN( parseFloat( N.val() ) ) && isFinite( N.val() ) && ( N.val() > 1 ) && !N.attr('disabled') ) {
	// 		N.val( parseInt( N.val() ) - 1 );
	// 	}
	// 	else {
	// 		N.val(1);
	// 	};
	// });



	// RTE YOUTUBE WRAPPER ======================================================================================================
	$(document).ready(function() {
		if ( $('.rte').length ) {
			$('.rte iframe[src *= youtube]').wrap('<div class="rte_youtube_wrapper"></div>');
		};
	});




	// BACK TO TOP BUTTON =======================================================================================================
	$(document).ready(function(){
		$(window).on('scroll', function(){
			if ( $(this).scrollTop() > 300 ) {
				$('#back_top').fadeIn("slow");
			}
			else {
				$('#back_top').fadeOut("slow");
			};
		});

		$('#back_top').on('click', function(e) {
			e.preventDefault();
			$('html, body').animate( {scrollTop : 0}, 800 );
			$('#back_top').fadeOut("slow").stop();
		});

	});




	// FORM VALIDATION ==========================================================================================================
	$.fn.formValidation = function() {
		this.find('input[type=text], input[type=email], input[type=password], textarea').after('<p class="alert-inline" style="display: none;"></p>');

		this.on('submit', function(event) {
			$(this).find('input[type=text], input[type=email], input[type=password], textarea').each(function() {

				if ( $(this).val() == '' ) {
					$(this).addClass('alert-inline').next().html('Field can\'t be blank').slideDown();

					$(this).on('focus', function() {
						$(this).removeClass('alert-inline').next().slideUp();
					});

					event.preventDefault();

				};

			});

			if ( $(this).find('input[type=email]').length ) {
				var inputEmail = $(this).find('input[type=email]');

				if ( inputEmail.val().length > 0 && ( inputEmail.val().length < 6 || inputEmail.val().indexOf("@") == -1 || inputEmail.val().indexOf(".") == -1 ) ) {
					inputEmail.addClass('alert-inline').next().html('Incorrect email').slideDown();

					inputEmail.on('focus', function() {
						$(this).removeClass('alert-inline').next().slideUp();
					});

					event.preventDefault();

				};

			};

			if ( $(this).find('input[type=password]').length == 2 ) {
				var pwd1 = $(this).find('input[type=password]:eq(0)');
				var pwd2 = $(this).find('input[type=password]:eq(1)');

				if ( pwd1.val() != pwd2.val() ) {
					pwd1.addClass('alert-inline');
					pwd2.addClass('alert-inline').next().html('Passwords do not match').slideDown();

					pwd1.on('focus', function() {
						pwd1.removeClass('alert-inline');
						pwd2.removeClass('alert-inline').next().slideUp();
					});

					pwd2.on('focus', function() {
						pwd1.removeClass('alert-inline');
						pwd2.removeClass('alert-inline').next().slideUp();
					});

					event.preventDefault();

				};

			};

		});

	};

	$('.footer_newsletter_form').formValidation();


	// SHOW/HIDE PASSWORD INPUT  ================================================================================================
	$(document).ready(function(){
		$('.file_eye_el').each(function(){
			let toggle = $(this);
			let elem = $(this).siblings('input');
			toggle.on('click', function(){
				if (elem.attr('type') == 'password') {
					elem.attr('type','text');
					toggle.addClass('see_text');
				} else {
					elem.attr('type','password');
					toggle.removeClass('see_text');
				}
			})
		});
		
	});



	// PRODUCT QUICK VIEW  ======================================================================================================
	jQuery(function($){
		$(document.body).on('click', '.quick_view_btn', function(e) {
			e.preventDefault();

			// CONSTRUCTING QUICK VIEW MODAL
				

			$(document.body).append("\n\t\t\t\t\u003cdiv id=\"product_quick_view\" style=\"display: none;\"\u003e\n\t\t\t\t\t\u003cdiv class=\"product_quick_wrapper\"\u003e\n\t\t\t\t\t\t\u003cdiv class=\"quick_view__left\"\u003e\n\t\t\t\t\t\t\t\u003cdiv id=\"img_big\"\u003e\u003c\/div\u003e\n\t\t\t\t\t\t\t\u003cdiv class=\"product_images\"\u003e\n\t\t\t\t\t\t\t\t\u003cdiv id=\"img_gallery\" class=\"swiper-container\"\u003e\n\t\t\t\t\t\t\t\t\t\u003cdiv class=\"swiper-wrapper\"\u003e\u003c\/div\u003e\n\t\t\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\t\u003cdiv class=\"quick_view__right\"\u003e\n\t\t\t\t\t\t\t\u003cform action=\"\/cart\/add\" method=\"post\" enctype=\"multipart\/form-data\" id=\"product-actions\" class=\"quick_view_form\"\u003e\n\t\t\t\t\t\t\t\t\u003cp id=\"quick_view__name\" class=\"product_name\"\u003e\u003c\/p\u003e\n\t\t\t\t\t\t\t\t\u003cp id=\"quick_view__price\" class=\"product_price\"\u003e\u003c\/p\u003e\n\t\t\t\t\t\t\t\t\u003cp id=\"quick_view__description\"\u003e\u003c\/p\u003e\n\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\u003cp id=\"quick_view__variants\"\u003e\n\t\t\t\t\t\t\t\t\t\u003clabel for=\"\"\u003eOptions:\u003c\/label\u003e\n\t\t\t\t\t\t\t\t\t\u003cselect id=\"product-select\" name=\"id\" class=\"hidden\"\u003e\u003c\/select\u003e\n\t\t\t\t\t\t\t\t\u003c\/p\u003e\n\t\t\t\t\t\t\t\t\u003cdiv id=\"quick_view_colors\"\u003e\u003c\/div\u003e\n\t\t\t\t\t\t\t\t\u003cdiv id=\"quick_view_size\"\u003e\u003c\/div\u003e\n\t\t\t\t\t\t\t\t\u003cdiv id=\"quick_view__form\"\u003e\n\t\t\t\t\t\t\t\t\t\u003clabel for=\"quantity\"\u003eQuantity:\u003c\/label\u003e\n\t\t\t\t\t\t\t\t\t\u003cdiv class=\"quantity_box\"\u003e\n\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\u003cinput min=\"1\" type=\"text\" name=\"quantity\" value=\"1\" class=\"quantity_input\" \/\u003e\n\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\t\t\t\t\u003cbutton class=\"btn btn_inverted btn-cart\" type=\"submit\" id=\"quick_view__add\"\u003e\u003csvg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http:\/\/www.w3.org\/2000\/svg\"\u003e\n\t\t\t\t\t\t\t\t\t\t\u003cpath d=\"M20 20H0V6H20V20ZM2 18H18V8H2V18Z\"\/\u003e\n\t\t\t\t\t\t\t\t\t\t\u003cpath d=\"M14 3.99995H12C12 3.49995 11.8 2.99995 11.4 2.59995C10.7 1.89995 9.3 1.89995 8.6 2.59995C8.2 2.89995 8 3.39995 8 3.99995H6C6 2.89995 6.4 1.89995 7.2 1.19995C8.7 -0.300049 11.3 -0.300049 12.9 1.19995C13.6 1.89995 14 2.89995 14 3.99995Z\"\/\u003e\n\t\t\t\t\t\t\t\t\t\u003c\/svg\u003e\n\t\t\t\t\t\t\t\t\tAdd to cart\n\t\t\t\t\t\t\t\t\t\u003c\/button\u003e\n\t\t\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\t\t\u003c\/form\u003e\n\t\t\t\t\t\t\t\u003cdiv id=\"product_info_link\" class=\"links_hover\"\u003e\n\t\t\t\t\t\t\t\t\u003ca href=\"#\"\u003eView Full Info\u003c\/a\u003e\n\t\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\t\u003c\/div\u003e\n\t\t\t\t\u003c\/div\u003e\n\t\t\t");

			// SHOWING FANCYBOX LOADING ANIMATION
			$.fancybox.showLoading();
			$.fancybox.helpers.overlay.open({parent: $('body')});

			// GETTING PRODUCT INFO (JSON)
			$.getJSON( $(this).attr( 'href' ) + '.js', function( product ) {

				// TOGGLE BIG IMAGE WHEN CLICKING A THUMB
				$(document).on('click', '#img_gallery a', function(e) {
					e.preventDefault();
					var newHREF = $(this).attr('href');
					$('#product_quick_view #img_big img').attr('src', newHREF );
				});

				// PRODUCT TITLE
				if ( product.title.length < 60 ) {
					var productTitle = product.title;
				}
				else {
					var productTitle = $.trim( product.title ).substring(0, 75) + '...';
				};
				$('#quick_view__name').html( '<a href="' + product.url + '">' + productTitle + '</a>' );


				// PRODUCT DESCRIPTION
				var productDescription = $.trim( product.description ).substring(0, 150) + '...';
				$('#quick_view__description').html( productDescription );

				// PRODUCT TYPE
				$('#quick_view__type span').html( product.type );

				// PRODUCT VENDOR
				$('#quick_view__vendor span').html( product.vendor );

				// PRODUCT VARIANTS
				$.each(product.variants, function(i, variant) {
					$('#product-select').append('<option value="' + variant.id + '">' + variant.title + ' - ' + variant.price + '</option>')
				});

				// PRODUCT ALL INFO LINK
				$('#product_info_link a').attr( 'href', product.url );

				// QUANTITY FORM MINI
				$("#quantity").on("focusout",function(){var t=$(this).val();$(this).val(isNaN(parseFloat(t))&&!isFinite(t)||0==parseInt(t)||""==t?1:parseInt(t)<0?parseInt(t)-2*parseInt(t):parseInt(t))}),$("#quantity_up").on("click",function(){var t=$("#quantity").val();$("#quantity").val(!isNaN(parseFloat(t))&&isFinite(t)?parseInt(t)+1:1)}),$("#quantity_down").on("click",function(){var t=$("#quantity").val();$("#quantity").val(!isNaN(parseFloat(t))&&isFinite(t)&&t>1?parseInt(t)-1:1)});

				// UPLOADING option_selection.js TO MANAGE PRODUCT VARIANTS
				$.getScript( "//cdn.shopify.com/shopifycloud/shopify/assets/themes_support/option_selection-fe6b72c2bbdd3369ac0bfefe8648e3c889efca213baefd4cfb0dd9363563831f.js", function() {

					// IMAGES PRELOADER (FUNCTION)
					function preloadImages(images, callback) {
						var count = images.length;
						if (count === 0) {
							callback();
						}
						var loaded = 0;
						$(images).each(function() {
							$('<img>').attr('src', this).load(function() {
								loaded++;
								if (loaded === count) {
									callback();
								}
							});
						});
					};

					// IMAGES PRELOADER (INIT)
					preloadImages( product.images, function() {

						// APPENDING BIG IMAGE
						var bigImgUrl = product.images[0].replace('.png', '_377x380_crop_center.png').replace('.jpg', '_377x380_crop_center.jpg');
						$('#product_quick_view #img_big').append( '<img src="' + bigImgUrl + '" alt="" />' );

						// APPENDING ALL IMAGES TO GALLERY
						$.each(product.images, function(i, src) {
							var smallSrc = src.replace('.png', '_68x82_crop_center.png').replace('.jpg', '_68x82_crop_center.jpg');
							var bigSrc = src.replace('.png', '_377x380_crop_center.png').replace('.jpg', '_377x380_crop_center.jpg');
							$('#img_gallery .swiper-wrapper').append( '<a class="swiper-slide" href="' + bigSrc + '"><img src="' + smallSrc + '" alt="" /></a>' );
						});

						// ADDING THUMBS SLIDER
						var quickViewGallery = new Swiper('#img_gallery', {
							slidesPerView: 5,
							spaceBetween: 10,
							slideToClickedSlide: true,
							direction: 'horizontal',
							height: 82,
							width: 370,
						});

						// VARIANT CHANGE FUNCTION
						var selectCallback = function(variant, selector) {
							if ( variant && variant.available ) {

								jQuery('#quick_view__add').removeAttr('disabled').removeClass('disabled');

								// VARIANT PRICES
								if( variant.price < variant.compare_at_price ){
									jQuery('#quick_view__price').html('<span class="money item_price">' + Shopify.formatMoney(variant.price, "") + '</span><span class="money compare-at-price money_sale">' + Shopify.formatMoney(variant.compare_at_price, "") + '</span><span class="money_sale_percent">Save' + parseInt( 100 - ( variant.price*100 )/variant.compare_at_price ) + '%</span>');
								}
								else {
									jQuery('#quick_view__price').html('<span class="money item_price">' + Shopify.formatMoney(variant.price, "") + '</span>');
								};

								// PRODUCT QUANTITY
								if ( variant.inventory_management != null ) {
									jQuery('#quick_view__availability span').removeClass('notify_danger').addClass('notify_success').html('Available');
								}
								else {
									jQuery('#quick_view__availability span').removeClass('notify_danger').addClass('notify_success').html( 'Available' );
								};
							} else {
	
								jQuery('#quick_view__add').addClass('disabled').attr('disabled', 'disabled'); // set add-to-cart button to unavailable class and disable button

								jQuery('#quick_view__availability span').removeClass('notify_success').addClass('notify_danger').html( 'Unavailable' );

								jQuery('#quick_view__price').html('<span class="money">' + Shopify.formatMoney(variant.price, "") + '</span>');
							};




							// COLOR & SIZE OPTIONS
							for (var i = 0; i < selector.product.options.length; i++) {
								if ( selector.product.options[i].name.toLowerCase() == 'color' ) {
										var selectorNum = i;
										var selectorName = selector.product.options[i].name;

									renderColorOptions(selectorNum, selectorName);
								};
								if ( selector.product.options[i].name.toLowerCase() == 'price' ) {
										var selectorNum = i;
										var selectorName = selector.product.options[i].name;

									renderSizeOptions(selectorNum, selectorName);
								};
							};


							// CHANGING VARIANT IMAGE
							if ( variant && variant.featured_image ) {

								var originalImage = $("#img_big img");
								var newImage = variant.featured_image;
								var element = originalImage[0];

								Shopify.Image.switchImage(newImage, element, function (newImageSizedSrc, newImage, element) {
									$('#img_big img').attr('src', newImageSizedSrc);
									quickViewGallery.slides.each(function(i) {
										var thumb = $(this).find('img').attr('src').replace('_68x82_crop_top', '').replace('_68x82_crop_center', '').replace('_68x82_crop_bottom', '').replace(/\?v=.*/ , '');
										var newImg = newImageSizedSrc.replace('_377x380', '').replace(/\?v=.*/ , '');
										
			
										if ( thumb == newImg ) {
											quickViewGallery.slideTo(i);
										};
									});
								});
							};

							currencyToggle('#quick_view__price .money');
						};

						// VARIANT CHANGE FUNCTION (INIT)
						new Shopify.OptionSelectors( "product-select",
						{
							product: product,
							onVariantSelected: selectCallback,
							enableHistoryState: false
						});

						// HIDING DEFAULT VARIANT SELECTOR
						$.each( $('#quick_view__variants select option'), function() {
							if ( $(this).val() == 'Default Title' ) {
								$('#quick_view__variants').hide();
							};
						});

						// SHOWING QUICK VIEW MODAL
						$.fancybox( $('#product_quick_view'),
							{
								'openSpeed': 500,
								'closeSpeed': 500,
								'tpl': {
									wrap: '<div id="quick_view__wrap" class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
									closeBtn : '<a title="Close" id="quick_view__close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
								},
								'afterClose': function() {
									$('#product_quick_view').remove(); // REMOVING QUICK VIEW MODAL AFTER CLOSE
								}
							}
						);

					});

				});


				function renderColorOptions(num, name) {
					var colorSelect = $('#product_quick_view .single-option-selector').eq(num);
					var selectId = '#' + colorSelect.attr('id');
					var container = $('#product_quick_view #quick_view_colors');
					var content = '<label>' + name + ': </label>';

					colorSelect.parent('.selector-wrapper').addClass('hidden');

					if ( $('#product_quick_view .single-option-selector').length == 1 ) {
						$('#quick_view__variants label').hide();
					}

					$('#product_quick_view ' + selectId + ' option' ).each(function(){
						var value = $(this).val();
						if ( colorSelect.val() == value ) {
							return content = content + '<div class="color_item current" data-val="' + value + '" title="' + value + '"><span class="color_inner" style="background-color: ' + value + '"></span></div>';
						} else {
							return content = content + '<div class="color_item" data-val="' + value + '" title="' + value + '"><span class="color_inner" style="background-color: ' + value + '"></span></div>';
						}
					});

					container.html(content);

					$('#product_quick_view .color_item').on('click', function(e){
						colorSelect.val( $(this).data('val') ).trigger('change');
					});
				};

				// RENDER SIZE OPTION
				function renderSizeOptions(num, name){
					var sizeSelect = $('#product_quick_view .single-option-selector').eq(num);
					var selectId = '#' + sizeSelect.attr('id');
					var container = $('#product_quick_view #quick_view_size');
					var content = '<label>' + name + ': </label>';

					sizeSelect.parent('.selector-wrapper').addClass('hidden');

					if ( $('#product_quick_view .single-option-selector').length == 1 ) {
						$('#quick_view__variants label').hide();
					}

					$('#product_quick_view ' + selectId + ' option' ).each(function(){
						var value = $(this).val();
						if ( sizeSelect.val() == value ) {
							return content = content + '<div class="size_item current" data-val="' + value + '"><span class="size_inner">' + value + '</span></div>';
						} else {
							return content = content + '<div class="size_item" data-val="' + value + '"><span class="size_inner">' + value + '</span></div>';
						};
					});

					container.html(content);

					$('#product_quick_view .size_item').on('click', function(e){
						sizeSelect.val( $(this).data('val') ).trigger('change');
					});
					
				};




				// CLOSING QUICK VIEW MODAL AFTER ADDING TO CART
				$('#quick_view__add').on('click', function() {
					$.fancybox.close();
				});

			});

		});

	});



	function currencyToggle(target) {
		// SWITCH CURRENCY
		if ( typeof theme.shopCurrency != 'undefined' ) {
			var newCurrency = Currency.cookie.read();
			Currency.convertAll( theme.shopCurrency, newCurrency, target, 'money_format' ); 
		}
	};



	// CHOOSE OPTIONS BUTTON   ==================================================================================================
	$(document.body).on('click', '.btn_options', function(e) {
		if ( $(window).width() >= 992 ) {
			$(this).parent().parent().parent().parent().find('.quick_view_btn').trigger('click');
			e.preventDefault();
		};
	});


//checkout

	// AJAX CART  ===============================================================================================================
	function ajaxCartRender() {
		$('.cart_content_preloader').removeClass('off');
		jQuery.getJSON('/cart.js', function(data) {
			var newHtml = '';

			if ( data.items.length == 0 ) {
				newHtml += '<p class="alert alert-warning">' + theme.cartAjaxTextEmpty + '</p>';
			} else {
				data.items.forEach( function( item, i ) {
					var image_url = item.image.replace('.png','_140x140_crop_center.png').replace('.jpg','_140x140_crop_center.jpg');
					newHtml += '<li class="cart_items"><img class="item_img" src="' + image_url + '"  alt="' + item.title + '" /><div class="item_desc"><a class="product_title" href="' + item.url + '">' + item.title.slice(0,50) + '</a><span class="money">' + Shopify.formatMoney(item.price, theme.moneyFormat) + '</span><p class="product_quantity">Quantity: ' + item.quantity + '</p><a class="item_remove_btn" href="#" item-id="' + item.id + '"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 0H13V2H18V4H2V2H7V0Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M4 20H16V6H4V20ZM14 18H6V8H14V18Z"/></svg></a></div></li>';
				});

				newHtml += '<div class="box_footer"><p class="cart_total"><b>' + theme.cartAjaxTextTotalPrice + ': </b><span class="money">' + Shopify.formatMoney(data.total_price, theme.moneyFormat)  + '</span></p><a id="clear_cart_all_items" class="cart_clear" href="/cart/clear"><i class="fa fa-refresh" title="' + theme.cartAjaxTextClearCart + '"></i></a><a class="btn btn_inverted" href="/checkout">Checkout</a><a class="btn" href="/cart">' + theme.cartAjaxTextGoCart + '</a></div>';
					
			}

			$('#cart_content_box').html(newHtml);
			$('.header_cart #cart_items').html(data.item_count).removeClass('hidden');
			removeItemFromCart();
			clearAllItemsFromCart();
			$('.cart_content_preloader').addClass('off');
		});
	};

	// REMOVE AJAX CART ITEMS
	function removeItemFromCart(){
		$('.header_cart .item_remove_btn').on('click', function(e){
			e.preventDefault();
			console.log('klick');
			itemId = this.getAttribute('item-id');
			var postData = "updates[" + itemId + "]=0";
			jQuery.post('/cart/update.js', postData, function(){
				ajaxCartRender();
				
			});
		});
	};
	removeItemFromCart();

	// CLEAR AJAX CART 
	function clearAllItemsFromCart(){
		$('#cart_content_box #clear_cart_all_items').on('click', function(e){
			e.preventDefault();

			jQuery.post('/cart/clear.js', function(){
				ajaxCartRender();
			});
		});
	};
	clearAllItemsFromCart();


	// JQUERY.AJAX-CART.JS MINI
	jQuery(document).ready(function(t){var e={TOTAL_ITEMS:".cart-total-items",TOTAL_PRICE:".cart-total-price",SUBMIT_ADD_TO_CART:"input[type=image], input.submit-add-to-cart",FORM_UPDATE_CART:"form[name=cartform]",FORM_UPDATE_CART_BUTTON:"form[name=cartform] input[name=update]",FORM_UPDATE_CART_BUTTONS:"input[type=image], input.button-update-cart",LINE_ITEM_ROW:".cart-line-item",LINE_ITEM_QUANTITY_PREFIX:"input#updates_",LINE_ITEM_PRICE_PREFIX:".cart-line-item-price-",LINE_ITEM_REMOVE:".remove a",EMPTY_CART_MESSAGE:"#empty"},a=function(t){return Shopify.formatMoney(t,"${{ amount }}")};t(document).on("submit",'form[action*="/cart/add"]',function(e){e.preventDefault(),t(e.target).find(".btn-cart").attr("disabled",!0).addClass("disabled"),Shopify.addItemFromForm(e.target)}),t(document).on("click",".btn-cart",function(){t.fancybox.showLoading(),t.fancybox.helpers.overlay.open({parent:t("body")})}),t(e.FORM_UPDATE_CART_BUTTON).click(function(a){a.preventDefault(),t(a.target.form).find(e.FORM_UPDATE_CART_BUTTONS).attr("disabled",!0).addClass("disabled"),Shopify.updateCartFromForm(a.target.form)}),t(e.FORM_UPDATE_CART).delegate(e.LINE_ITEM_REMOVE,"click",function(a){a.preventDefault();var i=this.href.split("/").pop().split("?").shift();Shopify.removeItem(i),t(this).parents(e.LINE_ITEM_ROW).remove()}),Shopify.onItemAdded=function(e,a){t(a).find(".btn-cart").attr("disabled",!1).removeClass("disabled"),Shopify.getCart()},Shopify.onCartUpdate=function(i,n){t("#cart_items").html(i.item_count);if(theme.cartAjaxOn){ajaxCartRender();}var r=a(i.total_price);t(e.TOTAL_PRICE).html(r),t(e.EMPTY_CART_MESSAGE).length>0&&0==i.item_count&&(t(e.FORM_UPDATE_CART).hide(),t(e.EMPTY_CART_MESSAGE).show()),n=n||!1,n&&i.item_count>0&&(t.each(i.items,function(i,n){t(e.LINE_ITEM_PRICE_PREFIX+n.id).html(a(n.line_price)),t(e.LINE_ITEM_QUANTITY_PREFIX+n.id).val(n.quantity)}),t(n).find("input[value=0]").parents(e.LINE_ITEM_ROW).remove(),t(n).find(e.FORM_UPDATE_CART_BUTTONS).attr("disabled",!1).removeClass("disabled"))},Shopify.onError=function(){t("form").find(".btn-cart").attr("disabled",!1).removeClass("disabled")}});





// JQUERY.API.JS MINI  ==========================================================================================================


// JQUERY.API.JS FULL  ==========================================================================================================
	function floatToString(t, o) {
		var r = t.toFixed(o).toString();
		return r.match(/^\.\d+/) ? "0" + r : r
	}

	function attributeToString(t) {
		return "string" != typeof t && (t += "", "undefined" === t && (t = "")), jQuery.trim(t)
	}
	"undefined" == typeof Shopify && (Shopify = {}), Shopify.money_format = "$ {{amount}}", Shopify.onError = function(XMLHttpRequest, textStatus) {
		var data = eval("(" + XMLHttpRequest.responseText + ")");
		alert(data.message + "(" + data.status + "): " + data.description)
	}, Shopify.onCartUpdate = function(t) {
		alert("There are now " + t.item_count + " items in the cart.")
	}, Shopify.onItemAdded = function(t) {
		alert(t.title + " was added to your shopping cart.")
	}, Shopify.onProduct = function(t) {
		alert("Received everything we ever wanted to know about " + t.title)
	}, Shopify.formatMoney = function(t, o) {
		var r = "",
			e = /\{\{\s*(\w+)\s*\}\}/,
			a = o || this.money_format;
		switch (a.match(e)[1]) {
			case "amount":
				r = floatToString(t / 100, 2).replace(/(\d+)(\d{3}[\.,]?)/, "$1 $2");
				break;
			case "amount_no_decimals":
				r = floatToString(t / 100, 0).replace(/(\d+)(\d{3}[\.,]?)/, "$1 $2");
				break;
			case "amount_with_comma_separator":
				r = floatToString(t / 100, 2).replace(/\./, ",").replace(/(\d+)(\d{3}[\.,]?)/, "$1.$2")
		}
		return a.replace(e, r)
	}, Shopify.resizeImage = function(t, o) {
		try {
			if ("original" == o) return t;
			var r = t.match(/(.*\/[\w\-\_\.]+)\.(\w{2,4})/);
			return r[1] + "_" + o + "." + r[2]
		} catch (e) {
			return t
		}
	}, Shopify.addItem = function(t, o, r) {
		o = o || 1;
		var e = {
			type: "POST",
			url: "/cart/add.js",
			data: "quantity=" + o + "&id=" + t,
			dataType: "json",
			success: function(t) {
				"function" == typeof r ? r(t) : Shopify.onItemAdded(t)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(e)
	}, Shopify.addItemFromForm = function(t, o) {
		var r = {
			type: "POST",
			url: "/cart/add.js",
			data: jQuery(t).serialize(),
			dataType: "json",
			success: function(r) {
				"function" == typeof o ? o(r, t) : Shopify.onItemAdded(r, t);

				$('body').append("\n\t\u003cdiv id=\"cart_added\"\u003e\n\t\t\u003ch4\u003e\n\t\t\t\u003csvg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http:\/\/www.w3.org\/2000\/svg\"\u003e\n\t\t\t\t\u003cpath d=\"M18 5.5L16.5714 4L8 13L3.42857 8L2 9.5L8 16L18 5.5Z\" fill=\"#349E53\"\/\u003e\n\t\t\t\u003c\/svg\u003e\n\t\t\tProduct successfully added to your shopping cart\n\t\t\u003c\/h4\u003e\n\t\t\u003cdiv class=\"cart_added__row\"\u003e\n\t\t\t\u003cdiv class=\"cart_added__1\" id=\"cart_added__img\"\u003e\n\t\t\t\t\u003cimg src=\"\" alt=\"\" \/\u003e\n\t\t\t\u003c\/div\u003e\n\t\t\t\u003cdiv class=\"cart_added__2\"\u003e\n\t\t\t\t\u003cspan id=\"cart_added__name\" class=\"product_name\"\u003e\u003c\/span\u003e\n\t\t\t\t\u003cspan id=\"cart_added__price\" class=\"product_price\"\u003e\u003c\/span\u003e\n\t\t\t\t\u003cp id=\"cart_added__quantity\"\u003e\n\t\t\t\t\tQuantity: 1\n\t\t\t\t\u003c\/p\u003e\n\t\t\t\t\u003cp id=\"cart_added__total_quantity\"\u003e\n\t\t\t\t\tThere are \u003cspan\u003e\u003c\/span\u003e items in your cart.\n\t\t\t\t\u003c\/p\u003e\n\t\t\t\t\u003cp id=\"cart_added__total_price\"\u003e\n\t\t\t\t\tTotal: \u003cspan\u003e\u003c\/span\u003e\n\t\t\t\t\u003c\/p\u003e\n\t\t\t\u003c\/div\u003e\n\t\t\t\u003cdiv class=\"cart_added__3\"\u003e\n\t\t\t\t\u003ca class=\"btn btn_alt\" id=\"cart_added__close\" href=\"#\"\u003eContinue shopping\u003c\/a\u003e\n\t\t\t\t\u003ca class=\"btn\" href=\"\/cart\"\u003eGo to cart\u003c\/a\u003e\n\t\t\t\u003c\/div\u003e\n\t\t\u003c\/div\u003e\n\t\u003c\/div\u003e\n");

				if ( r.title.length < 60 ) {
					var productTitle = r.title;
				}
				else {
					var productTitle = $.trim( r.title ).substring(0, 60) + '...';
				};

				$('#cart_added__name').html( productTitle );
				$('#cart_added__price').html( Shopify.formatMoney(r.price, theme.moneyFormat));
				$('#cart_added__total_quantity span').html( r.quantity ); 
				$('#cart_added__total_price span').html( Shopify.formatMoney(r.final_line_price, theme.moneyFormat));
				$('#cart_added__close').on( 'click', function(e) {
					e.preventDefault();

					$('.fancybox-close').trigger('click');
				});

				if ( r.image ) {
					$('#cart_added__img img').attr( 'src', r.image.replace('.jpg','_140x190_crop_center.jpg').replace('.png','_140x190_crop_center.png') ).load( function() { 
						$.fancybox.open( $('#cart_added'),
							{
								'openSpeed': 500,
								'closeSpeed': 300,
								'afterClose': function() {
									$('#cart_added').remove();
								}
							}
						);
					});
				}
				else {
					$('#cart_added__img').hide();
					$.fancybox.open( $('#cart_added'),
						{
							'openSpeed': 500,
							'closeSpeed': 300,
							'afterClose': function() {
								$('#cart_added').remove();
							}
						}
					);
				};

			},
			error: function(t, o) {
				Shopify.onError(t, o);

				var errorData = eval('(' + t.responseText + ')');

				$('body').append('<div id="cart_added" class="cart_error"><h4></h4><p class="alert alert-error"></p></div>');
				$('#cart_added h4').html( errorData.message );
				$('#cart_added p').html( errorData.description );

				$.fancybox.open( $('#cart_added'),
					{
						'openSpeed': 500,
						'closeSpeed': 300,
						'afterClose': function() {
							$('#cart_added').remove();
						}
					}
				);

			}
		};
		jQuery.ajax(r)
	}, Shopify.getCart = function(t) {
		jQuery.getJSON("/cart.js", function(o) {
			"function" == typeof t ? t(o) : Shopify.onCartUpdate(o)
		})
	}, Shopify.getProduct = function(t, o) {
		jQuery.getJSON("/products/" + t + ".js", function(t) {
			"function" == typeof o ? o(t) : Shopify.onProduct(t)
		})
	}, Shopify.changeItem = function(t, o, r) {
		var e = {
			type: "POST",
			url: "/cart/change.js",
			data: "quantity=" + o + "&id=" + t,
			dataType: "json",
			success: function(t) {
				"function" == typeof r ? r(t) : Shopify.onCartUpdate(t)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(e)
	}, Shopify.removeItem = function(t, o) {
		var r = {
			type: "POST",
			url: "/cart/change.js",
			data: "quantity=0&id=" + t,
			dataType: "json",
			success: function(t) {
				"function" == typeof o ? o(t) : Shopify.onCartUpdate(t)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(r)
	}, Shopify.clear = function(t) {
		var o = {
			type: "POST",
			url: "/cart/clear.js",
			data: "",
			dataType: "json",
			success: function(o) {
				"function" == typeof t ? t(o) : Shopify.onCartUpdate(o)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(o)
	}, Shopify.updateCartFromForm = function(t, o) {
		var r = {
			type: "POST",
			url: "/cart/update.js",
			data: jQuery(t).serialize(),
			dataType: "json",
			success: function(r) {
				"function" == typeof o ? o(r, t) : Shopify.onCartUpdate(r, t)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(r)
	}, Shopify.updateCartAttributes = function(t, o) {
		var r = "";
		jQuery.isArray(t) ? jQuery.each(t, function(t, o) {
			var e = attributeToString(o.key);
			"" !== e && (r += "attributes[" + e + "]=" + attributeToString(o.value) + "&")
		}) : "object" == typeof t && null !== t && jQuery.each(t, function(t, o) {
			r += "attributes[" + attributeToString(t) + "]=" + attributeToString(o) + "&"
		});
		var e = {
			type: "POST",
			url: "/cart/update.js",
			data: r,
			dataType: "json",
			success: function(t) {
				"function" == typeof o ? o(t) : Shopify.onCartUpdate(t)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(e)
	}, Shopify.updateCartNote = function(t, o) {
		var r = {
			type: "POST",
			url: "/cart/update.js",
			data: "note=" + attributeToString(t),
			dataType: "json",
			success: function(t) {
				"function" == typeof o ? o(t) : Shopify.onCartUpdate(t)
			},
			error: function(t, o) {
				Shopify.onError(t, o)
			}
		};
		jQuery.ajax(r)
	};






// WISHLIST ====================================================================================================================

    const t = $(".zemez_wishlist_total");
    zemezWishlist = async e => {
      if (e.dataset.action === "remove" && location.href !== e.href) {
        location = e.href;
        return;
      }
      e.setAttribute("loading", "");
      e.setAttribute("disabled", "");
      await fetch("https://prestashop7.devoffice.com/shopify/wishlist.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(e.dataset)
      })
        .then(r => r.json())
        .then(r => {
          t.removeClass('hidden').html(r.total);
          if (e.dataset.action === "remove" && location.href === e.href) {
            location = e.href;
            return;
          }

          e.dataset.action = r.action;
          e.removeAttribute("loading");
          e.removeAttribute("disabled");
        });
    };







// INSTAGRAM =======================================================================================================================
$.fn.homeSections = function() {
	sectionInstagram = function(target) {
		var feedWrap = target.find('.instafeed_wrap');
		var feedID = feedWrap.attr('id');
		var feedUser = feedWrap.data('user');
		var feedToken = feedWrap.data('token');
		var feedLimit = feedWrap.data('limit');

		window[feedID] = function() {
			var feed = new Instafeed({
				target: feedID,
				get: 'user',
				userId: feedUser,
				accessToken: feedToken,
				limit: feedLimit,
				template: '<div class="col-xs-2 instagram_item swiper-slide"><a href="{{link}}"><img src="{{image}}" alt=""><div class="info"><span class="likes"><i class="fa fa-heart" aria-hidden="true"></i>{{likes}}</span><span class="comments"><i class="fa fa-comment" aria-hidden="true"></i>{{comments}}</span></div></a></div>',
				resolution: 'low_resolution',
				error: function( data ) {
					document.getElementById(feedID).innerHTML = '<p class="alert alert-danger"><b>Instagram error</b><br>' + data + '</p>';
				},
				after: function() {
					instagramCarouselInit();
				}
			});
			feed.run();
		};

		window[feedID]();
	};

	if ( this.hasClass('section_instagram') ) {
		sectionInstagram(this);
	};
};

$(window).on('load', function() {
	$('.section_instagram').homeSections();
	//instagramCarouselInit();
});

$(document).on('shopify:section:load shopify:section:select', '.section_instagram', function() {
	$(this).homeSections();
});







// TABS ============================================================================================================================
$('.tab_content_wrapper').each(function(i) {
	var navItem = $(this).find('.tab_nav');
	var tabItem = $(this).find('.tab_item')
	navItem.on('click',function(e){
		navItem.removeClass('active');
		$(this).addClass('active');
		tabItem.removeClass('active');
		tabItem.eq($(this).data('tab')).addClass('active');
	});
});





// lINKLIST IN MENU TOGGLE  =======================================================================================================
if ( $(window).width() < 768 ) {
	$('.linklist_menu_title').each(function(){
		var navItem = $(this);
		var itemEl = navItem.parent().find('.list_links')
		itemEl.hide();
		navItem.on('click', function(){
			if (navItem.hasClass('open')) {
				itemEl.slideUp(400)
				navItem.removeClass('open');
			} else {
				itemEl.slideDown(400);
				navItem.addClass('open');
			}
		})
	});
}



// CLICKABLE COLOR OPTIONS =========================================================================================================
$('.color_options_clickable').on('click', function(){
	var variantImage = $(this).data('image');
	if (variantImage.length > 0) {
		$(this).parent().parent().parent().find('.product_img_wrap img').attr('src', variantImage);
	}
});


// SHOW POLICY PAGE   ==============================================================================================================
$('.item_policy__link').on('click', function(e){
	if ( $(window).width() > 992 ) {
		e.preventDefault();
		var link = $(this).attr('href');
		$.ajax({
			url:link,
			type:'GET',
			success: function(data){
				var content = $(data).find('.main_content').html();
				$.fancybox(content,{
					'maxWidth': 768
				});
			}
		});
	}
});

// ANY BLOCK CONTENT WRAPER
$('.wrap_triger').each(function(){
	var parentItem = $(this);
	var heirElement = parentItem.parent().find('.wrap_content')

	if ( $(window).width() < 640 ) {
		heirElement.slideUp(400);
		parentItem.removeClass('open');
	}

	parentItem.on('click', function(){
		if (parentItem.hasClass('open')) {
			heirElement.slideUp(400);
			parentItem.removeClass('open');
		} else {
			heirElement.slideDown(400);
			parentItem.addClass('open');
		}
	})

});


});




