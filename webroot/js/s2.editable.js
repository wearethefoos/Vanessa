(function(UI) {

  /** section: scripty2 ui
   *  class S2.UI.InPlaceEditor < S2.UI.Base
   *
   *
   *  A class for making elements editable.
   *
   *  <h4>Options</h4>
   *
   *  * `ajaxOptions` (`Object`): The options to be sent with Ajax requests.
   *  * `autoRows` (`Number`): If the `element` has multiple lines and the
   *    `rows` option is set to `1`, this is the number of rows the editor area
   *    will use. Default is `3`.
   *  * `clickToEditText`: The text that will be shown as the `title` attribute
   *    of the `element`. Defaults to `"Click to edit"`.
   *  * `cols` (`Number`): The number of columns used in a multi-line editor.
   *    Defaults to `40`.
   *  * `externalControl` (`Element` | `String`): An `Element` or element id to
   *    be used as an external control. When the element is clicked the editor
   *    will go into edit mode.
   *  * `externalControlOnly` (`Boolean`): Whether or not the editor will only
   *    have an external control and ignore the `controls` option.
   *  * `fieldPostCreation` (`String`): The action to apply to the edit field
   *    when edit mode is entered. Can be `"activate"`, `"focus"`, or `false`.
   *    Defaults to `"activate"`.
   *  * `formClassName` (`String`): The CSS class name given to the `form`
   *    element. Defaults to `"ui-ipe-form"`.
   *  * `formId` (`String`): The HTML ID or the `form` element.
   *  * `htmlResponse` (`Boolean`): Whether or not the response from the server
   *    will be processed as HTML. Defaults to `true`.
   *  * `paramName` (`String`): The parameter name that contains the value of
   *    the edit field when sent to the server. Defaults to `"value"`.
   *  * `rows` (`Number`): The number of rows to be used in a multi-line editor.
   *    If set to `1` and multi-line, the `autoRows` options will be used.
   *  * `savingClassName` (`String`): The CSS class given to the element during
   *    save. Defaults to `"ui-ipe-saving"`.
   *  * `savingText` (`String`): The text displayed in the element while saving.
   *    Defaults to `"Saving&hellip;"`
   *  * `size` (`Number`) : The `size` attribute to use for the edit field. If
   *    it is `0` the attribute will not be set. Defaults to `0`.
   *  * `submitOnBlur` (`Boolean`) : If this option is `true` the editor will
   *    save when the field is blurred. Defaults to `false`.
   *  * `textAfterControls` (`String`): Text to be displayed after the controls.
   *  * `textBeforeControls` (`String`): Text to be displayed before the
   *    controls.
   *  * `textBetweenControls` (`String`): Text to be displayed between each
   *    control.
   *  * `controls` (`Array`): A set of `Object`s that describe the controls
   *    placed after the edit field. By default an OK and Cancel button are
   *    used.
   *
  **/
  UI.InPlaceEditor = Class.create(UI.Base, {
    NAME: "S2.UI.InPlaceEditor",

    /**
     *  new S2.UI.InPlaceEditor(element, url, [options])
     *  - element (Element|String): The element or element id to be used for the
     *    editor.
     *  - url (String): The URL where requests to save edits wil be sent.
     *  - options (Object): Additional configuration options.
     *
     *  Instantiates an in place editor.
    **/
    initialize: function (element, url, options) {
      this.element = $(element);
      var opt = this.setOptions(options),
          contents = this.element.innerHTML;

      this.url = url;
      this._editing = false;
      this._saving = false;
      this._editor = null; // the input or text area
      this._controls = []; // the buttons and/or links
      this.observers = { click: this._click.bind(this) };
      this.element.store(this.NAME, this);
      this.element.store("ui.ipe.originalBackground",
        S2.CSS.colorFromString(this.element.getStyle("background-color"))
      );
      this.element.store("ui.ipe.originalContents", contents);
      this.element.store("ui.ipe.originalTitle",
        this.element.readAttribute("title"));
      this.element.writeAttribute("title", opt.clickToEditText);

      UI.addClassNames(this.element, 'ui-widget ui-ipe');
      UI.addBehavior(this.element, UI.Behavior.Hover);
      this.addObservers();
      this.setText(contents);
    },

    addObservers: function () {
      var external = $(this.options.externalControl);
      Object.keys(this.observers).each(function (o) {
        this.element.observe(o, this.observers[o]);
      }.bind(this));
      if (Object.isElement(external)) {
        external.observe("click", this.edit.bind(this));
      }
    },

    removeObservers: function () {
      Object.keys(this.observers).each(function (o) {
        this.element.stopObserving(o, this.observers[o]);
      }.bind(this));
      if (Object.isElement(this._form)) this._form.stopObserving("submit");
      if (Object.isElement(this._editor)) {
        this._editor.stopObserving("blur");
        this._editor.stopObserving("keydown");
      }
      // Stop observing controls
      this._controls.each(function (control) {
        try {
          control.stopObserving("click");
          control.element.stopObserving("click");
        } catch (e) {} // Control was not a widget or element or something else
                       // happened
      });
    },

    /**
     * S2.UI.InPlaceEditor#getText() -> String
     *
     * Get the text currently being used by the editor as a `String`.
    **/
    getText: function () {
      var text = this._text.unescapeHTML();
      text = text.replace(/^\s+/,'');
      text = text.replace(/\s+$/,'');
      return text;
    },

    /**
     * S2.UI.InPlaceEditor#setText(text) -> this
     *
     * Changes the text for the editor.
    **/
    setText: function (text) {
      this._text = text;
      this.element.store("ui.ipe.previousContents", text);
      return this;
   },

    /**
     * S2.UI.InPlaceEditor#edit([event]) -> this
     * fires ui:ipe:enter
     *
     * Send the editor into edit mode.
    **/
    edit: function (event) {
      var externalControl = $(this.options.externalControl);
      if (this._editing || this._saving) return;
      var result = this.element.fire("ui:ipe:enter", {
        inPlaceEditor: this
      });
      if (!result.stopped) {
        this._editing = true;
        if (Object.isElement(externalControl)) externalControl.hide();
        this.element.hide();
        this._createForm();
        this._editor.setValue(this.getText());
        this.element.insert({ before: this._form });
        var fpc = this.options.fieldPostCreation;
        this._editor[fpc ? fpc : "activate"]();
      }

      if (event) event.stop();
      return this;
    },

    /**
     * S2.UI.InPlaceEditor#stopEditing([event]) -> this
     * fires ui:ipe:leave
     *
     * Take the editor out of edit mode.
    **/
    stopEditing: function (event) {
      var externalControl = $(this.options.externalControl);
      if (!this._editing) return;
      var result = this.element.fire("ui:ipe:leave", {
        inPlaceEditor: this
      });
      if (!result.stopped) {
          this._editing = false;
          this._form.remove();
          this.element.show();
          if (Object.isElement(externalControl)) externalControl.show();
      }

      if (event) event.stop();
      return this;
    },

    /**
     * S2.UI.InPlaceEditor#save([event]) -> this
     * fires ui:ipe:before:save
     * fires ui:ipe:after:save
     *
     * Save the text from the editor to the server and leave edit mode.
    **/
    save: function (event) {
      var result = this.element.fire("ui:ipe:before:save", {
        inPlaceEditor: this
      }), ajaxOptions = this.options.ajaxOptions;

      if (!result.stopped) {
        this._saving = true;
        this.stopEditing();
        this.element.update(this.options.savingText);
        this.element.addClassName("ui-ipe-saving");
        // TODO: callback for params instead of serialize
        // TODO: wrap onComplete/onFailure callbacks
        if (!Object.isFunction(ajaxOptions.onComplete)) {
          ajaxOptions.onComplete = function (transport) {
            result = this.element.fire("ui:ipe:after:save", {
              inPlaceEditor: this
            });
            if (!result.stopped) {
              this.element.removeClassName("ui-ipe-saving");
              this.setText(transport.responseText);
              this.element.update(this.getText());
              this._saving = false;
            }
          }.bind(this);
        }
        if (!Object.isFunction(ajaxOptions.onFailure)) {
          ajaxOptions.onFailure = function (transport) {
            //TODO: event
            alert("Error communicating with server: " +
              String(transport.responseText).stripTags());
          }.bind(this);
        }
        if (this.options.htmlResponse) ajaxOptions.evalScripts = true;
        Object.extend(ajaxOptions, {
          parameters: { editorId: this.element.identify() }
        });
        this._form.request(ajaxOptions);
      }
      if (event) event.stop();
      return this;
    },

    /**
     * S2.UI.InPlaceEditor#cancel([event]) -> this
     * fires ui:ipe:cancel
     *
     * Take the editor out of edit mode and revert the text back to its previous
     * contents.
    **/
    cancel: function (event) {
      var result = this.element.fire("ui:ipe:cancel", {
        inPlaceEditor: this
      });
      if (!result.stopped) {
        this.stopEditing();
        if (this._saving) this._saving = false;
        this.element.update(this.element.retrieve("ui.ipe.previousContents"));
      }
      if (event) event.stop();
      return this;
    },

    /**
     * S2.UI.InPlaceEditor#destroy() -> undefined
     *
     * Remove all editor components and revert the element back to its original
     * state.
    **/
    destroy: function() {
      var storage = this.element.getStorage();
      this.removeObservers();
      if (this._editing) this.cancel();
      if (Object.isElement(this._form)) delete this._form;
      this.element.update(this.element.retrieve("ui.ipe.originalContents"));
      this.element.writeAttribute("title",
        this.element.retrieve("ui.ipe.originalTitle"));
      UI.removeBehavior(this.element, UI.Behavior.Hover);

      // Remove classnames
      // FIXME: If the element is another widget, this will remove the
      // ui-widget class
      this.element.classNames().each(function (c) {
        if (c.startsWith("ui-")) this.element.removeClassName(c);
      }, this);
      // Remove data stored on element
      storage.keys().each(function (k) {
        if (String(k).startsWith("ui.ipe.")) { storage.unset(k); }
      });
    },

    _click: function (event) {
       this.edit(event);
    },

    _createForm: function () {
      if (Object.isElement(this._form)) return;
      var form = new Element("form", {
        id: this.options.formId,
        "class": this.options.formClassName + " ui-widget",
        action: this.url,
        method: this.options.ajaxOptions.method || "post"
      });
      form.observe("submit", this.save.bind(this));
      this._form = form;
      // TODO: Move these methods into this one
      this._createEditor();
      // TODO: onFormCustomization
      this._createControls();
    },

    // TODO: Comment for clarity
    _createEditor: function () {
      var opt = this.options, rows = parseInt(opt.rows, 10), type, afterText;
      var elementOptions = { name: opt.paramName };
      if (rows <= 1 && !/\r|\n/.test(this.getText())) { // INPUT
        type = "INPUT";
        Object.extend(elementOptions, {
          type: "text",
          size: opt.size
        });
        afterText = "&nbsp";
      } else { // TEXTAREA
        type = "TEXTAREA";
        Object.extend(elementOptions, {
          rows: rows > 1 ? rows: opt.autoRows,
          cols: opt.cols
        });
        afterText = "<br />";
      }
      var editor = new Element(type, elementOptions);
      editor.observe("keydown", this._checkForEscapeOrReturn.bind(this));
      this._editor = editor;
      if (opt.submitOnBlur) editor.observe("blur", this.save.bind(this));
      this._form.insert({ top: editor, bottom: afterText });
    },

    // TODO: Comment for clarity
    _createControls: function () {
      var controls = this._controls, opts = this.options.controls, text = "",
          form = this._form, between = this.options.textBetweenControls;

      function insert(item) { form.insert({ bottom: item }); }

      if (this.options.externalControlOnly) opts = [];
      insert(this.options.textBeforeControls);
      opts.each(function (opt, i) {
        var el, control, last = i === opts.length - 1;
        if (opt.type === "button") {
          el = new Element(opt.type, { type: opt.type });
          control = new S2.UI.Button(el, {
            primary: opt.primary,
            seconary: opt.secondary
          });
        } else if (opt.type === "link") {
          el = new Element("a", { href: "#" });
          control = { element: el };
        } else { // Anything else (string or element) should be inserted as-is
          control = { element: opt };
        }
        el.update(opt.label);
        control.element.observe("click", function (event) {
          var action = opt.action;
          event.stop();
          if (Object.isFunction(action)) action(this);
          else if (Object.isString(action)) this[action](event);
        }.bind(this));
        controls.push(control);
        insert(control.element);
        if (!last) insert(between);
      }, this);
      insert(this.options.textAfterControls);
    },

    _checkForEscapeOrReturn: function (event) {
      var e = event;
      if (!this._editing || e.ctrlKey || e.altKey || e.shiftKey) return;
      if (Event.KEY_ESC === e.keyCode) this.cancel(e);
      else if (Event.KEY_RETURN === e.keyCode) this.save(e);
    }
  });

  Object.extend(UI.InPlaceEditor, {
    DEFAULT_OPTIONS: {
      ajaxOptions: {},
      autoRows: 3,                         // Use when multi-line w/ rows == 1
      clickToEditText: 'Click to edit',
      cols: 40,
      fieldPostCreation: 'activate',       // 'activate'|'focus'|false
      formClassName: 'ui-ipe-form',
      htmlResponse: true,
      paramName: 'value',
      rows: 1,                             // If 1 and multi-line, uses autoRows
      savingClassName: 'ui-ipe-saving',
      savingText: 'Saving&hellip;',
      size: 0,
      textAfterControls: '',
      textBeforeControls: '',
      textBetweenControls: '&nbsp;',
      controls: [
        { type: "button", label: "OK", primary: true, action: "save" },
        { type: "button", label: "Cancel", secondary: true, action: "cancel" }
      ]
    }
  });

})(S2.UI);