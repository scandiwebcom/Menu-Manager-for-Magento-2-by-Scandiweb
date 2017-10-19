/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true expr:true*/
define([
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/modal/modal',
    'mage/backend/validation',
    'mage/adminhtml/events'
], function ($) {
    'use strict';

    return {
        targetElement: '.edit-menu-item',
        $urlTypeElement: '[data-action=menu-item-url-type]',
        $formId: '#item_form',
        options: {
            customUrl: '.field-url',
            categorySelect: '.field-category_id',
            cmsPageSelect: '.field-cms_page_identifier'
        },
        activeUrlType: 0,

        /**
         * Constructor component
         * @param {Object} data - this backend data
         */
        'Scandiweb_Menumanager/item-actions-handler': function (data) {
            this.initActions();
        },

        /**
         * Initialize ajaxified modal window slide action
         */
        initActions: function () {
            var self = this;

            $(document).on('click', this.targetElement, function (evt) {
                self.stopEvent(evt);

                var requestUrl = $(this).attr('href');
                self.open(requestUrl, $(this));
            });
        },

        /**
         * Initialize field toggling based on provided value.
         * 
         * @private
         */
        _initUrlTypeHandler: function() {
            var self = this;

            $(this.$urlTypeElement).on('change', function (evt) {
                self.activeUrlType = $(this).val();
                self._toggleSelector();
            });
        },

        /**
         * Initialize form validation
         * 
         * @private
         */
        _initFormValidation: function() {
            $(this.$formId).form()
                .validation({
                    validationUrl: '',
                    highlight: function (element) {
                        var detailsElement = $(element).closest('details');
                        if (detailsElement.length && detailsElement.is('.details')) {
                            var summaryElement = detailsElement.find('summary');
                            if (summaryElement.length && summaryElement.attr('aria-expanded') === "false") {
                                summaryElement.trigger('click');
                            }
                        }

                        $(element).trigger('highlight.validate');
                    }
                });
        },

        open: function (requestUrl, elementId) {
            if (requestUrl && elementId) {
                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    showLoader: true,
                    dataType: 'html',
                    success: function (data, textStatus, transport) {
                        this._openDialogWindow(data, elementId);
                    }.bind(this),
                    complete: function() {
                        this._initFormValidation();
                        this._initUrlTypeHandler();
                        this._toggleSelector();

                        $(this.$urlTypeElement).trigger('change');
                    }.bind(this)
                });
            }
        },

        _openDialogWindow: function (data, elementId) {
            var self = this;

            if (this.modal) {
                this.modal.html($(data).html());
            } else {
                this.modal = $(data).modal({
                    title: $.mage.__('Edit Item'),
                    modalClass: 'menu_item',
                    type: 'slide',
                    firedElementId: elementId,
                    buttons: [
                        {
                            text: $.mage.__('Close'),
                            click: function () {
                                this.closeModal();
                            }
                        },
                        {
                            text: $.mage.__('Save'),
                            class: 'action-primary',
                            click: function () {
                                $(self.$formId).submit();
                            }
                        }
                    ]
                });
            }

            this.modal.modal('openModal');
        },

        _toggleSelector: function() {
            var activeSelector = null,
                self = this;

            switch (parseInt(self.activeUrlType)) {
                case 0:
                    activeSelector = self.options.customUrl;
                    break;
                case 1:
                    activeSelector = self.options.cmsPageSelect;
                    break;
                case 2:
                    activeSelector = self.options.categorySelect;
                    break;
                default:
                    activeSelector = self.options.customUrl;
                    break;
            }

            $.each(this.options, function (index, element) {
                self.disableElement($(element));
            });

            self.enableElement($(activeSelector));
        },

        disableElement: function (elm) {
            elm.addClass('ignore-validate').prop('disabled', true);
            elm.hide();
        },

        enableElement: function (elm) {
            elm.removeClass('ignore-validate').prop('disabled', false);
            elm.show();
        },

        stopEvent: function (e, stopPropagation) {
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);
            stopPropagation && e.stopPropagation && e.stopPropagation();
        }
    };
});
