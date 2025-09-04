var UxperFieldsConfig = UxperFieldsConfig || {};

(function ($) {
  "use strict";
  UxperFieldsConfig.fieldInstance = [];

  /**
   * Process required field
   */
  UxperFieldsConfig.required = {
    applyField: [],
    init: function () {
      this.processApplyField();
      this.onChange();
    },
    processApplyField: function () {
      $(".uxper-field[data-required]").each(function () {
        var $this = $(this),
          required = $this.data("required"),
          fieldId = $this.attr("id"),
          i,
          j,
          requiredChild,
          requiredGrandChild,
          _name,
          _op,
          _value;
        if ($.isArray(required[0])) {
          for (i = 0; i < required.length; i++) {
            requiredChild = required[i];
            if ($.isArray(requiredChild[0])) {
              for (j = 0; j < requiredChild.length; j++) {
                requiredGrandChild = requiredChild[j];
                _name = requiredGrandChild[0];
                _op = requiredGrandChild[1];
                _value = requiredGrandChild[2];

                if (_name.indexOf("[") != -1) {
                  _name = _name.replace(/\[.*/i, "");
                }

                if (
                  typeof UxperFieldsConfig.required.applyField[_name] ===
                  "undefined"
                ) {
                  UxperFieldsConfig.required.applyField[_name] = [];
                }
                if (
                  UxperFieldsConfig.required.applyField[_name].indexOf(
                    fieldId
                  ) === -1
                ) {
                  UxperFieldsConfig.required.applyField[_name].push(fieldId);
                }

                if (_op[0] === "&") {
                  if (
                    typeof UxperFieldsConfig.required.applyField[_value] ===
                    "undefined"
                  ) {
                    UxperFieldsConfig.required.applyField[_value] = [];
                  }
                  if (
                    UxperFieldsConfig.required.applyField[_value].indexOf(
                      fieldId
                    ) === -1
                  ) {
                    UxperFieldsConfig.required.applyField[_value].push(fieldId);
                  }
                }
              }
            } else {
              _name = requiredChild[0];
              _op = requiredChild[1];
              _value = requiredChild[2];

              if (_name.indexOf("[") != -1) {
                _name = _name.replace(/\[.*/i, "");
              }

              if (
                typeof UxperFieldsConfig.required.applyField[_name] ===
                "undefined"
              ) {
                UxperFieldsConfig.required.applyField[_name] = [];
              }
              if (
                UxperFieldsConfig.required.applyField[_name].indexOf(
                  fieldId
                ) === -1
              ) {
                UxperFieldsConfig.required.applyField[_name].push(fieldId);
              }
              if (_op[0] === "&") {
                if (
                  typeof UxperFieldsConfig.required.applyField[_value] ===
                  "undefined"
                ) {
                  UxperFieldsConfig.required.applyField[_value] = [];
                }
                if (
                  UxperFieldsConfig.required.applyField[_value].indexOf(
                    fieldId
                  ) === -1
                ) {
                  UxperFieldsConfig.required.applyField[_value].push(fieldId);
                }
              }
            }
          }
        } else {
          _name = required[0];
          _op = required[1];
          _value = required[2];

          if (_name.indexOf("[") != -1) {
            _name = _name.replace(/\[.*/i, "");
          }

          if (
            typeof UxperFieldsConfig.required.applyField[_name] === "undefined"
          ) {
            UxperFieldsConfig.required.applyField[_name] = [];
          }
          if (
            UxperFieldsConfig.required.applyField[_name].indexOf(fieldId) === -1
          ) {
            UxperFieldsConfig.required.applyField[_name].push(fieldId);
          }
          if (_op[0] === "&") {
            if (
              typeof UxperFieldsConfig.required.applyField[_value] ===
              "undefined"
            ) {
              UxperFieldsConfig.required.applyField[_value] = [];
            }
            if (
              UxperFieldsConfig.required.applyField[_value].indexOf(fieldId) ===
              -1
            ) {
              UxperFieldsConfig.required.applyField[_value].push(fieldId);
            }
          }
        }
      });
    },
    onChange: function () {
      $(".uxper-field").on(
        "uxper_check_required",
        UxperFieldsConfig.required.onChangeEvent
      );
    },
    onChangeEvent: function (event) {
      if (this != event.target) {
        return;
      }
      var $this = $(this),
        fieldId = $this.attr("id");
      if (typeof $this.data("field-value") == "undefined") {
        return;
      }
      if (
        typeof UxperFieldsConfig.required.applyField[fieldId] === "undefined"
      ) {
        return;
      }
      var i,
        $cloneField = $this.closest(".uxper-clone-field-panel");
      if ($cloneField.length) {
        for (
          i = 0;
          i < UxperFieldsConfig.required.applyField[fieldId].length;
          i++
        ) {
          UxperFieldsConfig.required.toggleField(
            $(
              "#" + UxperFieldsConfig.required.applyField[fieldId][i],
              $cloneField
            ),
            $cloneField
          );
        }
      } else {
        for (
          i = 0;
          i < UxperFieldsConfig.required.applyField[fieldId].length;
          i++
        ) {
          UxperFieldsConfig.required.toggleField(
            $("#" + UxperFieldsConfig.required.applyField[fieldId][i]),
            $cloneField
          );
        }
      }
    },
    toggleField: function ($field, $cloneField) {
      var required = $field.data("required"),
        isVisible = true;
      if (!$.isArray(required[0])) {
        isVisible = UxperFieldsConfig.required.processField(
          required,
          $cloneField
        );
      } else {
        isVisible = UxperFieldsConfig.required.andCondition(
          required,
          $cloneField
        );
      }
      if (isVisible) {
        $field.slideDown();
      } else {
        $field.slideUp();
      }
    },
    andCondition: function (required, $cloneField) {
      var requiredChild, i;
      for (i = 0; i < required.length; i++) {
        requiredChild = required[i];
        if (!$.isArray(requiredChild[0])) {
          if (
            !UxperFieldsConfig.required.processField(requiredChild, $cloneField)
          ) {
            return false;
          }
        } else {
          if (
            !UxperFieldsConfig.required.orCondition(requiredChild, $cloneField)
          ) {
            return false;
          }
        }
      }
      return true;
    },
    orCondition: function (required, $cloneField) {
      var requiredChild, i;
      for (i = 0; i < required.length; i++) {
        requiredChild = required[i];
        if (
          UxperFieldsConfig.required.processField(requiredChild, $cloneField)
        ) {
          return true;
        }
      }
      return false;
    },
    processField: function (required, $cloneField) {
      var _field = required[0],
        _op = required[1],
        _val = required[2],
        fieldVal,
        _field_key = "";
      if (_field.indexOf("[") != -1) {
        var _field_temp = _field.replace(/\[.*/i, "");
        _field_key = _field.substring(_field_temp.length);
        _field_key = _field_key.substr(1, _field_key.length - 2);
        _field = _field_temp;
      }

      if ($cloneField.length) {
        fieldVal = $("#" + _field, $cloneField).data("field-value");
      } else {
        fieldVal = $("#" + _field).data("field-value");
      }
      if (_field_key !== "" && typeof fieldVal[_field_key] !== "undefined") {
        fieldVal = fieldVal[_field_key];
      }

      if (_op.substr(0, 1) === "&") {
        if ($cloneField.length) {
          _val = $("#" + _val, $cloneField).data("field-value");
        } else {
          _val = $("#" + _val).data("field-value");
        }
      }

      // _op: =, !=, in, not in, contain, not contain
      // _op start with "&": reference to field (_val)
      switch (_op) {
        case "=":
        case "&=":
          return _val == fieldVal;
        case "!=":
        case "&!=":
          return _val != fieldVal;
        case "in":
        case "&in":
          return (
            _val == fieldVal ||
            ($.isArray(_val) && _val.indexOf(fieldVal) != -1)
          );
        case "not in":
        case "&not in":
          return (
            (!$.isArray(_val) && _val != fieldVal) ||
            _val.indexOf(fieldVal) == -1
          );
        case "contain":
        case "&contain":
          return (
            _val == fieldVal ||
            ($.isArray(fieldVal) && fieldVal.indexOf(_val) != -1) ||
            (typeof fieldVal === "object" && _val in fieldVal)
          );
        case "not contain":
        case "&not contain":
          return (
            (!$.isArray(fieldVal) && fieldVal != _val) ||
            fieldVal.indexOf(_val) == -1
          );
      }
      return false;
    },
    checkRequired: function ($field, value) {
      $field.data("field-value", value);
      $field.trigger("uxper_check_required");
      $field.trigger("uxper_check_preset");
      $field.trigger("Uxper_Field_change");
    },
  };

  UxperFieldsConfig.preset = {
    init: function () {
      this.onChange();
    },
    onChange: function () {
      $(".uxper-fields-wrapper").on(
        "uxper_check_preset",
        ".uxper-field",
        function (event) {
          if (this != event.target) {
            return;
          }
          var $this = $(this),
            $panel = $this.closest(".uxper-clone-field-panel");

          if ($panel.length === 0) {
            $panel = $(".uxper-fields-wrapper");
          }

          if (typeof $this.data("field-value") == "undefined") {
            return;
          }
          var dataPreset = $this.data("preset");
          if (typeof dataPreset == "undefined") {
            return;
          }
          var fieldValue = $this.data("field-value"),
            i,
            j,
            _op,
            _value,
            _fields;
          for (i = 0; i < dataPreset.length; i++) {
            _op = dataPreset[i]["op"];
            _value = dataPreset[i]["value"];
            _fields = dataPreset[i]["fields"];
            if (
              (_op === "=" && _value == fieldValue) ||
              (_op === "!=" && _value != fieldValue)
            ) {
              for (j = 0; j < _fields.length; j++) {
                var $field = $panel.find("#" + _fields[j][0]);
                $field.find("[data-field-control]").val(_fields[j][1]);
                $field
                  .find("[data-field-control]")
                  .trigger("uxper_preset_change", _fields[j][1]);
                $field.find("[data-field-control]").trigger("change");
              }
              break;
            }
          }
        }
      );
    },
  };

  /**
   * Clone Field
   */
  UxperFieldsConfig.cloneField = {
    cloneTemplate: [],
    init: function () {
      this.makeCloneTemplate();
      this.addButton();
      this.removeButton();
      this.sortableField();
    },
    makeCloneTemplate: function (item) {
      var cloneIndex = 0;
      $(".uxper-field-content-inner-clone").each(function () {
        var fieldClone = $("> .uxper-clone-field:last", this);
        if (fieldClone.length > 0) {
          UxperFieldsConfig.cloneField.cloneTemplate[cloneIndex] =
            fieldClone[0].outerHTML;
          $(this).attr("data-clone-template", cloneIndex);
          cloneIndex++;
        }
      });

      var $configWrapper = $(".uxper-meta-config-wrapper");
      $configWrapper = $configWrapper.length ? $configWrapper : $("body");

      $configWrapper.trigger("uxper_make_template_done");
    },
    makeCloneTemplateElement: function ($item) {
      var cloneIndex = UxperFieldsConfig.cloneField.cloneTemplate.length + 1;
      $item.each(function () {
        var fieldClone = $("> .uxper-clone-field:last", this);
        if (fieldClone.length > 0) {
          UxperFieldsConfig.cloneField.cloneTemplate[cloneIndex] =
            fieldClone[0].outerHTML;
          $(this).attr("data-clone-template", cloneIndex);
          cloneIndex++;
        }
      });
      $item.trigger("uxper_make_template_item_done");
    },

    reIndexFieldName: function ($wrapper, isRepeater) {
      var cloneIndex = 0,
        isCloneInner = false,
        isClonePanel = false;
      var $field = $wrapper.closest(".uxper-field");
      if ($field.hasClass("uxper-panel")) {
        isClonePanel = true;
      } else {
        if ($field.closest(".uxper-panel").length) {
          isCloneInner = true;
        }
      }
      if (!isClonePanel) {
        $(".uxper-clone-field", $wrapper).each(function () {
          $(':input[name$="]"]', this).each(function () {
            if (isCloneInner) {
              var $this = $(this),
                fullName = $this.attr("name"),
                after = fullName.replace(/\w+\[\d+\]\[\w+\]/i, ""),
                name = fullName.substring(0, fullName.length - after.length),
                affix = after.replace(/^\[\d+\]/i, "");
              $this.attr("name", name + "[" + cloneIndex + "]" + affix);

              var panelIndex = $(this)
                .closest(".uxper-clone-field")
                .parent()
                .closest(".uxper-clone-field")
                .data("panel-index");
              name = $this.attr("name").replace(/\[.*/i, "");
              affix = $this.attr("name").replace(/[^\]]*\]/i, "");
              $this.attr("name", name + "[" + panelIndex + "]" + affix);
            } else {
              var $this = $(this);
              var name = $this.attr("name").replace(/\[.*/i, "");
              var affix = $this.attr("name").replace(/[^\]]*\]/i, "");
              $this.attr("name", name + "[" + cloneIndex + "]" + affix);
            }
          });
          cloneIndex++;
        });
      } else {
        cloneIndex = 0;
        $("> .uxper-clone-field", $wrapper).each(function () {
          $(this).data("panel-index", cloneIndex);
          $(':input[name$="]"]', this).each(function () {
            var $this = $(this);
            var name = $this.attr("name").replace(/\[.*/i, "");
            var affix = $this.attr("name").replace(/[^\]]*\]/i, "");
            $this.attr("name", name + "[" + cloneIndex + "]" + affix);
          });
          cloneIndex++;
        });
      }

      if (isRepeater) {
        $('input[type="hidden"]', $wrapper).val(cloneIndex);
      }
    },
    removeButton: function ($element) {
      if (typeof $element === "undefined") {
        $element = $(".uxper-fields-wrapper");
      }

      $element.find(".uxper-clone-button-remove").on("click", function () {
        var $parent = $(this).parent();
        var $wrapper = $parent.parent();
        $parent.remove();
        UxperFieldsConfig.cloneField.reIndexFieldName(
          $wrapper,
          $(this).hasClass("uxper-is-repeater")
        );
      });
    },
    addButton: function () {
      $(".uxper-clone-button-add").on("click", function () {
        var $parent = $(this).parent().find("> .uxper-field-content-inner");
        if (typeof $parent.attr("data-clone-template") == "undefined") {
          return;
        }
        var cloneIndex = parseInt($parent.attr("data-clone-template"), 10);
        var $lastCloneField = $("> .uxper-clone-field:last", $parent);
        var $element = $(
          UxperFieldsConfig.cloneField.cloneTemplate[cloneIndex]
        );

        if ($lastCloneField.length == 0) {
          $parent.prepend($element);
        } else {
          $lastCloneField.after($element);
        }
        UxperFieldsConfig.cloneField.removeButton($element);
        UxperFieldsConfig.cloneField.emptyElementValue($element);

        UxperFieldsConfig.cloneField.reIndexFieldName(
          $parent,
          $(this).hasClass("uxper-is-repeater")
        );
        $element.trigger("uxper_add_clone_field");
      });
    },
    emptyElementValue: function ($element) {
      $("[data-field-control]", $element).each(function () {
        var $field = $(this).closest(".uxper-field");
        $field.data("field-value", "");
        if ($field.hasClass("uxper-field-text")) {
          $(this).val("");
        } else if ($field.hasClass("uxper-field-select")) {
          $(this).prop("selectedIndex", 0);
        }
      });
    },
    sortableField: function () {
      $(".uxper-field-content-inner-clone").sortable({
        placeholder: "uxper-field-clone-sortable-placeholder",
        handle: ".uxper-sortable-button",
        update: function (event, ui) {
          var $wrapper = $(event.target);
          UxperFieldsConfig.cloneField.reIndexFieldName($wrapper, false);
        },
      });
    },
  };

  /**
   * Group Field Process
   */
  UxperFieldsConfig.group = {
    init: function () {
      this.toggle();
    },
    toggle: function () {
      $(".uxper-group-toggle")
        .closest("h4")
        .on("click", function () {
          var $this = $(this),
            $toggleIcon = $this.find(".uxper-group-toggle"),
            $inner = $this.next(".uxper-group-inner");
          $toggleIcon
            .toggleClass("dashicons-arrow-up")
            .toggleClass("dashicons-arrow-down");
          $inner.slideToggle();
        });
    },
  };

  /**
   * Other Fields
   */
  UxperFieldsConfig.fields = {
    /**
     * Get value of field
     * @param $field
     * @param input
     * @returns {string}
     */
    getValue: function ($field) {
      var input = "[data-field-control]",
        value = "",
        $firstField = $(input + ":first", $field),
        fieldType = $firstField.attr("type"),
        fieldName = $firstField.attr("name"),
        fieldMap = $field.data("field-map"),
        isMultiple = fieldName.match(/\[\]$/i);
      if (fieldMap != "") {
        fieldMap = fieldMap.split(",");
      }

      if (typeof fieldType === "undefined") {
        fieldType = "";
      }
      fieldType = fieldType.toLowerCase();
      var isOptionInput =
        fieldType == "radio" || fieldType == "checkbox" ? true : false;

      if ($(".uxper-clone-field", $field).length) {
        value = [];
        $(".uxper-clone-field", $field).each(function () {
          if (isMultiple) {
            var valueChild = [];
            $(input, this).each(function () {
              if (isOptionInput) {
                // Only checkbox
                if ($(this).prop("checked")) {
                  valueChild.push($(this).val());
                } else {
                  valueChild.push("");
                }
              } else {
                valueChild.push($(this).val());
              }
            });
            value.push(valueChild);
          } else {
            if (fieldMap.length) {
              valueChild = [];
              for (var mapIndex = 0; mapIndex < fieldMap.length; mapIndex++) {
                var $thisControl = $(
                  input + '[name$="[' + fieldMap[mapIndex] + ']"]',
                  this
                );

                fieldType = $thisControl.attr("type");
                if (typeof fieldType === "undefined") {
                  fieldType = "";
                }
                fieldType = fieldType.toLowerCase();
                isOptionInput =
                  fieldType == "radio" || fieldType == "checkbox"
                    ? true
                    : false;

                if (isOptionInput) {
                  if ($thisControl.prop("checked")) {
                    valueChild[fieldMap[mapIndex]] = $thisControl.val();
                  } else {
                    if (!$thisControl.data("uncheck-novalue")) {
                      value[fieldMap[mapIndex]] = "";
                    }
                    valueChild[fieldMap[mapIndex]] = "";
                  }
                } else {
                  valueChild[fieldMap[mapIndex]] = $thisControl.val();
                }
              }
              value.push(valueChild);
            } else {
              if (isOptionInput) {
                if (fieldType === "radio") {
                  var _noVal = true;
                  $(input, this).each(function () {
                    if ($(this).prop("checked")) {
                      value.push($(this).val());
                      _noVal = false;
                    }
                  });
                  if (_noVal) {
                    value.push("");
                  }
                } else {
                  if ($(input, this).prop("checked")) {
                    value.push($(input, this).val());
                  } else {
                    value.push("");
                  }
                }
              } else {
                value.push($(input, this).val());
              }
            }
          }
        });
      } else {
        if (isMultiple) {
          value = [];
          if (isOptionInput) {
            // Only checkbox
            $(input, $field).each(function () {
              if ($(this).prop("checked")) {
                value.push($(this).val());
              } else {
                value.push("");
              }
            });
          } else {
            $(input, $field).each(function () {
              value.push($(this).val());
            });
          }
        } else {
          if (fieldMap.length) {
            value = [];
            for (var mapIndex = 0; mapIndex < fieldMap.length; mapIndex++) {
              var $thisControl = $(
                input + '[name$="[' + fieldMap[mapIndex] + ']"]',
                $field
              );
              fieldType = $thisControl.attr("type");
              if (typeof fieldType === "undefined") {
                fieldType = "";
              }
              fieldType = fieldType.toLowerCase();
              isOptionInput =
                fieldType == "radio" || fieldType == "checkbox" ? true : false;

              if (isOptionInput) {
                if ($thisControl.prop("checked")) {
                  value[fieldMap[mapIndex]] = $thisControl.val();
                } else {
                  if (!$thisControl.data("uncheck-novalue")) {
                    value[fieldMap[mapIndex]] = "";
                  }
                }
              } else {
                value[fieldMap[mapIndex]] = $thisControl.val();
              }
            }
          } else {
            if (isOptionInput) {
              if (fieldType === "radio") {
                $(input, $field).each(function () {
                  if ($(this).prop("checked")) {
                    value = $(this).val();
                  }
                });
              } else {
                if ($(input, $field).prop("checked")) {
                  value = $(input, $field).val();
                }
              }
            } else {
              value = $(input, $field).val();
            }
          }
        }
      }
      return value;
    },
  };

  /**
   * Tabs for metabox
   * @type {{init: Function}}
   */
  UxperFieldsConfig.tabs = {
    init: function () {
      this.toggle();
      setTimeout(function () {
        UxperFieldsConfig.tabs.changeWidthContent();
      }, 100);
    },
    toggle: function () {
      $(".uxper-tab a").on("click", function (event) {
        var idCurrent = $(
          ".uxper-fields-wrapper > div.uxper-section-container:visible"
        ).attr("id");
        event.preventDefault();
        if (typeof event.currentTarget.hash != "undefined") {
          $("#" + idCurrent).hide();
          $(event.currentTarget.hash).fadeIn();
        }
        $(".uxper-tab li").removeClass("active");
        $(this).parent().addClass("active");
        $(this).trigger("uxper-tab-clicked");
      });
    },
    changeWidthContent: function () {
      var $tab = $(".uxper-tab");
      if ($tab.length > 0) {
        var $wrap = $(".uxper-meta-box-wrap"),
          $fields = $(".uxper-fields"),
          tabWidth = $tab.outerWidth(),
          wrapWidth = $wrap.width();
        $fields.css({
          float: "left",
          width: wrapWidth - tabWidth + "px",
          overflow: "visible",
        });
      }
    },
  };

  UxperFieldsConfig.onReady = {
    init: function () {
      UxperFieldsConfig.cloneField.init();
      UxperFieldsConfig.group.init();
      UxperFieldsConfig.required.init();
      UxperFieldsConfig.preset.init();
      UxperFieldsConfig.tabs.init();
      $(".uxper-field").trigger("uxper_check_required");
    },
  };
  UxperFieldsConfig.onResize = {
    init: function () {
      UxperFieldsConfig.tabs.changeWidthContent();
    },
  };
  $(document).ready(UxperFieldsConfig.onReady.init);
  $(window).resize(UxperFieldsConfig.onResize.init);
})(jQuery);
