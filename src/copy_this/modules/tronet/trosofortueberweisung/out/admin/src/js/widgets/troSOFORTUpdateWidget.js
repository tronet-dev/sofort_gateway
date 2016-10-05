/**
 * troSOFORTUpdateWidget for module tronet/trosofortueberweisung.
 *
 * Provides mechanism to iterate through a list and triggers defined actions. It's currently
 * working with SOFORT module only.
 *
 * @package modules
 * @author tronet GmbH
 * @since 7.0.0
 * @todo Make usage of jQuery dialog box instead of old annoying alert box.
 * @todo Refactor code so it can be used more widely.
 */
$.widget('custom.troSOFORTUpdateWidget', {
    options: {
        actionListId: '',
        actionListElement: '',
        actionList: [],
        nextActionId: 0,
        fatalError: false, // update process won't be continued
        waitForUserInput: false, // a dialog box appears with ok|cancel buttons: @todo implement this feature
        shopMainUrl: '',
        securityToken: '',
        moduleVersion: '',

        // error and help messages
        errorHintDefault: '',
        changedCoreFileQuestion: '',
        changedCoreFileHint: '',
        couldNotCreateBackupQuestion: '',
        couldNotReactivateModule: '',
        couldNotClearOxidTmpDir: ''
    },
    _create: function () {
        this.troLoadActionList();
        this.troPerformNextAction();

        if (this.option('fatalError')) {
            $('.tro-sofort-update-failedtoupdated').fadeIn();
        }
        else {
            $('.tro-sofort-update-successfullyupdated').fadeIn();
        }
    },

    /**
     * Loads actions from defined list into widget.
     *
     * @author tronet GmbH
     */
    troLoadActionList: function () {
        // Declare & initialize local vars
        var $sActionListId = this.option('actionListId');
        var $sActionListElement = '#' + $sActionListId + ' > li';
        var $aActionList = [];

        // Load actions from list into widget
        $($sActionListElement).each(function ($index) {
            $aActionList[$index] = $($sActionListElement)[$index];
        });

        // Update options
        this.option('actionListElement', $sActionListElement);
        this.option('actionList', $aActionList);
    },

    /**
     * Performs current action.
     *
     * @author tronet GmbH
     */
    troPerformNextAction: function () {
        if (this.option('fatalError') == false) {
            /*
             * Declare & initialize local vars
             */
            // Actions and such
            var $iCurrentActionIndex = this.option('nextActionId');
            var $sElement = $(this.option('actionListElement'))[$iCurrentActionIndex];
            var $sAction = $sElement.getAttribute('data-tro-action');
            var $sCompleteUrl = this.troGetActionUrl($sAction);

            var $sActionListId = this.option('actionListId');
            var $sActionListElement = '#' + $sActionListId + ' > li';

            // Back questions and hints
            var $sErrorHintDefault = this.option('errorHintDefault');
            var $sChangedCoreFileQuestion = this.option('changedCoreFileQuestion');
            var $sChangedCoreFileHint = this.option('changedCoreFileHint');

            var $sCouldNotCreateBackupQuestion = this.option('couldNotCreateBackupQuestion');
            var $sCouldNotReactivateModule = this.option('couldNotReactivateModule');
            var $sCouldNotClearOxidTmpDir = this.option('couldNotClearOxidTmpDir');

            // Other
            var $blFatalError = false;

            // Remove current action css class
            $($sElement).removeClass('tro-todo');

            // Do the ajax call @todo Move it to an own method?
            $.ajax({
                url: $sCompleteUrl,
                dataType: 'json',
                async: false,
                success: function ($data) {
                    var $blChangedCoreFiles = false;
                    var $blCouldNotCreateBackup = false;
                    var $blCouldNotRefreshModule = false;
                    var $blCouldNotClearOxidTmpDir = false;
                    var $aErrorItems = [];

                    $.each(JSON.parse($data), function ($key, $val) {
                        switch ($key) {
                            case 'changedCoreFiles':
                                $blChangedCoreFiles = ($val > 0);
                                break;

                            case 'couldNotCreateBackup':
                                $blCouldNotCreateBackup = ($val == "1" || $val == 1);
                                break;

                            case 'couldNotRefreshModule':
                                $blCouldNotRefreshModule = ($val == "1");
                                break;

                            case 'couldNotClearOxidTmpDir':
                                $blCouldNotClearOxidTmpDir = ($val == "1");
                                break;

                            default:
                                $aErrorItems.push("<li id=" + $key + ">" + $val + "</li>");
                        }
                    });

                    if ($aErrorItems.length > 0) {
                        var $sErrorHintText = $sErrorHintDefault;

                        // Depending on error/warning show prompt/alert box
                        if ($blChangedCoreFiles) {
                            $sErrorHintText = $sChangedCoreFileHint;

                            if (confirm($sChangedCoreFileQuestion) == false) {
                                $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed');
                                $blFatalError = true;
                            }
                            else {
                                $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed-but-continued-by-users-request');
                            }
                        }
                        else if ($blCouldNotCreateBackup) {
                            $sErrorHintText = $sCouldNotCreateBackupQuestion;

                            if (confirm($sCouldNotCreateBackupQuestion) == false) {
                                $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed');
                                $blFatalError = true;
                            }
                            else {
                                $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed-but-continued-by-users-request');
                            }
                        }
                        else if ($blCouldNotRefreshModule) {
                            $sErrorHintText = $sCouldNotReactivateModule;

                            alert($sCouldNotReactivateModule);
                            $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed-but-continued-by-users-request');
                        }
                        else if ($blCouldNotClearOxidTmpDir) {
                            $sErrorHintText = $sCouldNotClearOxidTmpDir;

                            alert($sCouldNotClearOxidTmpDir);
                            $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed-but-continued-by-users-request');
                        }
                        else {
                            $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed');
                            $blFatalError = true;
                        }

                        // Append hint text on error/warning
                        var $sCurrentActionHtml = $($($sActionListElement)[$iCurrentActionIndex]).html();
                        var $sCurrentActionHtmlNewContent = $sCurrentActionHtml + '<br /><br /><u>' + $sErrorHintText + '</u><br /><br />';
                        $($($sActionListElement)[$iCurrentActionIndex]).html($sCurrentActionHtmlNewContent);

                        // Append error/warning items
                        $("<ul/>", {
                            html: $aErrorItems.join("")
                        }).appendTo($($sActionListElement)[$iCurrentActionIndex]);

                    }
                    else {
                        $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-successful');
                    }
                },
                error: function () {
                    $blFatalError = true;
                    $($($sActionListElement)[$iCurrentActionIndex]).addClass('tro-failed');
                }
            });

            // Update options
            this.option('nextActionId', this.option('nextActionId') + 1);
            this.option('fatalError', $blFatalError);
        }

        this.troPerformNextActionAfter();
    },

    /**
     * Actions performed after "troPerformNextAction".
     *
     * @author tronet GmbH
     */
    troPerformNextActionAfter: function () {
        if (this.option('fatalError') == false) {
            if (this.option('actionList').length == this.option('nextActionId')) {
                $('#tro-sofort-update-successfullyupdated').fadeIn();
            }
            else {
                this.troPerformNextAction();
            }
        }
        else {
            $('#tro-sofort-update-failedtoupdated').fadeIn();
        }
    },

    /**
     * Getter for the oxid backend url and get-parameter required for update process.
     *
     * @param {string} $sAction
     * @returns {string}
     *
     * @author tronet GmbH
     */
    troGetActionUrl: function ($sAction) {
        return this.option('shopMainUrl') + "admin/index.php?cl=trosofortueberweisung_update&fnc=" + $sAction
            + "&stoken=" + this.option('securityToken') + "&trosofortueberweisung_version=" +
            this.option('moduleVersion');
    }
});