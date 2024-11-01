jQuery(document).ready(function ($) {

    if ( $('#create-space-form-wrapper').length ) {
        let wrapper = $('#create-space-form-wrapper');
        let current = wrapper.attr('data-current');
        let tabs = wrapper.find('.space-create-buttons');

        tabs.find("[data-id='" + current + "']").addClass('current button primary');

        const queryString = window.location.search;
        if (queryString) {
            const urlParams = new URLSearchParams(queryString);
            const from_space = urlParams.get('settings-tab')
            if (from_space === 'true') {
                $('.prev-next').children(":first").hide();
                $('.prev-next').children().text(spaces_engine_main.return);
                $('.prev-next').children().attr("href", function(i, href) {
                    return href + '/settings';
                });

            }
        }

    }

    $( "body" ).on("click", '#space-settings-submit', function (e) {
        e.preventDefault();

        window.tinyMCE.triggerSave();
        var form = $(this).closest('#space-settings');
        var nonce = form.attr('data-nonce');
        var formData = form.serialize();

        $.ajax(
            {
                type: "POST",
                dataType: "JSON",
                url: spaces_engine_main.ajaxurl,
                data: {
                    action: "save_space",
                    nonce: nonce,
                    form_data: formData,
                },
                success: function ($response) {
                    var feedback = form.find('.bp-feedback');
                    if ( $response.success && $response.data ) {
                        feedback.hide();
                        location.reload();
                    } else if ( ! $response.success && $response.data ) {
                        console.log($response.data[0].code);
                        feedback.find('p').text($response.data[0].message);
                        feedback.show();
                    }
                },
            }
        );
    });

    $( "body" ).on("click", '#delete-space-button', function (e) {
        e.preventDefault();

        var form = $(this).closest('#space-settings');
        var nonce = form.attr('data-nonce');
        var space_id = $('input[name="space_id"]').val();

        $.ajax(
            {
                type: "POST",
                dataType: "JSON",
                url: spaces_engine_main.ajaxurl,
                data: {
                    action: "delete_space",
                    space_id: space_id,
                    nonce: nonce,
                },
                success: function ($response) {
                    var feedback = form.find('.save-feedback');
                    if ( $response.success && $response.data ) {
                        feedback.hide();
                        window.location.replace($response.data.url);
                    } else if ( ! $response.success && $response.data ) {
                        console.log($response.data[0].code);
                        feedback.find('p').text($response.data[0].message);
                        feedback.show();
                    }
                },
            }
        );
    });

    $( "body" ).on("click", '.space-cover-image-save', function (e) {
        var saveButton = $(e.currentTarget);
        var coverImage = $(e.currentTarget).closest('#cover-image-container').find('.header-cover-img');
        var post_id = $(e.currentTarget).closest('#item-header').attr('data-bp-item-id');
        saveButton.addClass('loading');

        console.log(saveButton);

        $.ajax(
            {
                type: "POST",
                dataType: "JSON",
                url: spaces_engine_main.ajaxurl,
                data: {
                    action: "space_save_cover_position",
                    position: coverImage.attr( 'data-top' ),
                    post_id: post_id,
                },
                success: function ($response) {
                    if ( $response.success && $response.data && '' !== $response.data.content ) {
                        saveButton.closest( '#header-cover-image:not(.has-position)' ).addClass( 'has-position' );
                        coverImage.css( { 'top': $response.data.content + 'px' } );
                    }
                },
            }
        );
    });

    $( "body" ).on("click", '#create-space-form-wrapper .prev-next button[type=submit]', function (e) {
            let step = $('#create-space-form-wrapper').attr('data-current');

            if ( 'details' === step ) {
                create_space(e);
            }
    }
    );

    // Did we have a tab open before the refresh?
    var active_tab = localStorage.getItem('space-setting-active-tab');
    if (active_tab != null) {
        $('#space-settings-tabs-nav li#space-setting-' + active_tab + '-li').addClass('active');
        $('.space-settings-content .settings-tab-content').hide();
        $('.space-settings-content .settings-tab-content:first').hide();
        // Should the submit button be shown?
        if ($('.space-settings-content .settings-tab-content#space-setting-' + active_tab + '-content').hasClass('no-submit')) {
            $('#space-settings-submit').hide();
        }
        $('#space-setting-' + active_tab + '-content').css('display', 'block');
    } else {
        // Show the first tab and hide the rest
        if ($('#space-settings-tabs-nav li.active').length > 0) {
            let active_setting_tab = $('#space-settings-tabs-nav li.active').data('id');
            $('.space-settings-content .settings-tab-content').hide();
            $('.space-settings-content .settings-tab-content#space-setting-' + active_setting_tab + '-content').show();
        } else {
            $('#space-settings-tabs-nav li:first-child').addClass('active');
            $('.space-settings-content .settings-tab-content').hide();
            $('.space-settings-content .settings-tab-content:first').show();
        }
    }
    // Remove the active tab if refreshed a second time
    localStorage.removeItem('space-setting-active-tab');
    // Show action wrapper here, to prevent fouc
    $('.space-settings-action-wrapper').show();
    // Add a border width here to prevent a flash of an empty bordered container
    $('.space-settings .item-body-inner').css('border-width', '1px');

    /* Action button toggleables */
    $('.space-action-button .space-action-info').hide();

    $('#space-action-' + $('input[name="space_info[action-button]"]:checked').val()).show();
    $(document).on(
        'change',
        '.space-action-button .space-action-radio',
        function (event) {
            $('.space-action-info').hide();
            let val = $(this).val()
            $('#space-action-' + val).show();
        }
    );

    // Click function
    $(document).on(
        'click',
        '#space-settings-tabs-nav li',
        function (e) {
            let active_setting_tab = $(this).attr('data-id');
            // Set the clicked tab as active after refresh
            localStorage.setItem('space-setting-active-tab', active_setting_tab);

            $('#space-settings-submit').show();
            // Should the submit button be shown?
            if ($('.space-settings-content .settings-tab-content#space-setting-' + active_setting_tab + '-content').hasClass('no-submit')) {
                $('#space-settings-submit').hide();
            }
            $('#space-settings-tabs-nav li').removeClass('active');
            $(this).addClass('active');
            $('.space-settings-content .settings-tab-content').hide();

            var activeTab = $(this).find('a').attr('href');
            $(activeTab).fadeIn();
            return false;
        }
    );

    // $( "body" ).on("click", '#space-settings-tabs-nav li', function (e) {
    //     let active = $(this).attr('data-id');
    //     $(this).addClass('active');
    //
    //     if ($('#tabs-content #space-setting-' + active + '-content').hasClass('no-submit')) {
    //         $('.space-settings-submit').hide();
    //     } else {
    //         $('.space-settings-submit').show();
    //     }
    //
    //     $('#tabs-content .tab-content').removeClass('active');
    //     $('#tabs-content #space-setting-' + active + '-content').addClass('active');
    //
    //     return false;
    // });

    function create_space(e) {
        window.tinyMCE.triggerSave();

        let wrapper = $('#create-space-form-wrapper');
        let form = wrapper.find('#create-space-form');
        let title    = form.find('#space-name').val();
        let description    = form.find('#space-description').val();
        let category    = form.find('#wpe-wps-category-dropdown').val();
        let nonce   = form.attr( "data-nonce" );

        $.ajax(
            {
                type: "POST",
                dataType: "JSON",
                url: spaces_engine_main.ajaxurl,
                data: {
                    title: title,
                    description: description,
                    category: category,
                    nonce: nonce,
                    action: "create_space",
                },
                success: function (response) {
                    if ( ! response.success) {
                        console.log( response );
                        $('.bp-feedback p').text( response.data[0].message);
                        $('.bp-feedback').removeClass('success');
                        $('.bp-feedback').addClass('error');
                        $('.bp-feedback').show();
                    } else {
                        $('.bp-feedback p').text( response.data.message);
                        $('.bp-feedback').removeClass('error');
                        $('.bp-feedback').addClass('success');
                        $('#space-visit').attr('href', response.data.url);
                        $('#create-space-form')[0].reset();
                        $('#create-space-form :input').prop('disabled', true);
                        tinymce.activeEditor.setMode('readonly');
                        $('#space-submit').hide();
                        $('.bp-feedback').show();
                        $('#create-space-result').show();
                        $('#create-space-form-wrapper .prev-next button[type=submit]').addClass('disabled');
                        $('#create-space-form-wrapper .prev-next .next').attr("href", function(i, href) {
                            return href + response.data.slug;
                        });
                        $('#create-space-form-wrapper .prev-next .next').removeClass('disabled');
                    }
                },
            }
        );
    }

    if ( $('#space-archive-container').length > 0) {
        function get_posts(params = null) {
            $container  = $( "#space-archive-container" );

            nonce = $container.data('nonce');

            search_terms = $container.find( "#wpe-wps-spaces-search" ).val();
            category = $container.find( "#wpe-wps-category-dropdown" ).val();
            pagination = $container.data('pagination');

            order = $('#wpe-wps-index-ordering').val();

            if ($("#wpe-wps-index-personal").hasClass("selected")) {
                scope = "personal";
            } else if ($("#wpe-wps-index-all").hasClass("selected")) {
                scope = "all";
            }

            // Get the current page. This is passed via the params parameter.
            if (params && params.paged) {
                page = params.paged;
            } else {
                page = 1;
            }

            params = {
                nonce: nonce,
                page: page,
                scope: scope,
                category: category,
                order: order,
                search_terms: search_terms,
            };

            $content    = $container.find( ".space-archive-wrapper" );
            $action_bar = $container.find( ".spaces-type-navs" );
            $status     = $container.find( ".status" );

            $.ajax(
                {
                    url: spaces_engine_main.ajaxurl,
                    data: {
                        action: 'filter_spaces',
                        nonce: nonce,
                        params: params,
                        pagination: pagination,
                    },
                    type: "post",
                    dataType: "json",
                    success: function (response) {
                        if ( ! response.success) {
                            console.log(response.data[0].code);
                            $status.html(response.data[0].message);
                        } else {
                            $status.hide();
                            $content.html( response.data.content );
                            $content.show();
                        }
                    },
                    error: function (response, textStatus) {
                        console.log(response.data.message);
                        $status.html( textStatus );
                    },
                    complete: function (data, textStatus) {
                        msg = textStatus;

                        $action_bar
                            .find( "#wpe-wps-index-all a .count" )
                            .html( data.responseJSON.found );
                        $action_bar
                            .find( "#wpe-wps-index-personal a .count" )
                            .html( data.responseJSON.found_author );
                    },
                }
            );
        }

        $( '#space-archive-container' ).on(
            "click",
            "#wpe-wps-index-reset",
            function (e) {
                e.preventDefault();

                reset_filters();

                get_posts();

                $('#space-archive-container').removeClass('filters-open');
            }
        );

        $( '#space-archive-container' ).on(
            "click",
            ".pagination a",
            function (e) {
                e.preventDefault();

                // creates a object from the array, one of the properies (search) contains the query
                let url = new URL($(this).attr( "href" ));
                // will create a object of all availalble query properites
                const urlSearchParams = new URLSearchParams(url.search);
                const params = Object.fromEntries(urlSearchParams.entries());

                get_posts(params);
            }
        );

        $( document ).on(
            "click",
            "#wpe-wps-index-filter",
            function (e) {
                e.preventDefault();

                get_posts();

                $('#space-archive-container').removeClass('filters-open');
            }
        );

        $('#wpe-wps-spaces-search').on('keyup change', function () {
            get_posts();
            return false;
        });


        $( document ).on(
            "click",
            ".wpe-wps-index-scope-link",
            function (e) {
                e.preventDefault();

                if ($( this ).attr( "id" ) == "wpe-wps-index-all") {
                    $( "#wpe-wps-index-personal" ).removeClass( "selected" );
                    $( "#wpe-wps-index-all" ).addClass( "selected" );
                } else if ($( this ).attr( "id" ) == "wpe-wps-index-personal") {
                    $( "#wpe-wps-index-all" ).removeClass( "selected" );
                    $( "#wpe-wps-index-personal" ).addClass( "selected" );
                }

                get_posts();
            }
        );

        $( document ).on(
            "change",
            "#wpe-wps-index-ordering",
            function (e) {
                e.preventDefault();

                get_posts();
            }
        );

        $( document ).on(
            "change",
            "#wpe-wps-category-dropdown",
            function (e) {
                e.preventDefault();

                get_posts();
            }
        );

        $( document ).ready(
            function () {
                get_posts();
            }
        );
    }
});