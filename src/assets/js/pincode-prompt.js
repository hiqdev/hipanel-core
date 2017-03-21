(function ($, window, document, undefined) {
	var pluginName = "pincodePrompt",
		config = {
			modal: '.pincode-modal',
			submit: '.pincode-modal .pincode-submit',
			input: '.pincode-modal .pincode-input'
		};

	function Plugin(element) {
		this.element = $(element);

		this.modal = $(config.modal);
		this.submit = $(config.submit);
		this.input = $(config.input);

		this.promise = undefined;
		this.init();

		return this.askPincode();
	}

	Plugin.prototype = {
		init: function () {

		},
        askPincode: function () {
			var promise = this.getPromise();
            this.showModal();

			return promise;
        },
        addError: function (text) {
            this.modal.find('.form-group').addClass('has-error');
            this.modal.find('.help-block').text(text);
        },
		clearError: function () {
            this.modal.find('.form-group').removeClass('has-error');
            this.modal.find('.help-block').text('');
		},
		clearInput: function () {
			this.input.val('');
			this.input.off('keyup.pincodePrompt')
		},
        takePincode: function () {
			var pincode = this.input.val();

			if (pincode === '') {
				this.addError('');
				return;
            }

            this.getPromise().resolve(pincode);
		},
		reject: function () {
            this.hideModal();
            this.getPromise().reject();
            this.promise = undefined;
		},
		getPromise: function () {
			var that = this;

			if (!this.promise) {
                this.promise = $.Deferred();
                this.promise.always(function () {
                	that.resetPrompt();
                });
            }

            return this.promise;
		},
		showModal: function () {
			var that = this;

            this.modal
	            .one('show.bs.modal.pincodePrompt', function () {
                    that.input.focus();
                })
                .one('hide.bs.modal.pincodePrompt', function () {
                    that.reject();
                })
	            .modal('show');

			this.submit.one('click.pincodePrompt', this.takePincode.bind(this));
			this.input.on('keyup.pincodePrompt', function (e) {
				if (e.keyCode === 13) {
                    that.takePincode();
				}
            });
		},
		hideModal: function () {
			this.modal.modal('hide');
		},
		resetPrompt: function () {
			this.hideModal();
			this.clearError();
			this.clearInput();
			this.promise = undefined;
		}
	};

	$.fn[pluginName] = function (options) {
		var plugin = 'plugin_' + pluginName;
		if (!$(this).data(plugin)) {
			$(this).data(plugin, new Plugin(this, options));
		}

		return $(this).data(plugin);
	};
})(jQuery, window, document);
