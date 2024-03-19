import './bootstrap'
import 'jstree'
// import '@selectize/selectize/dist/css/selectize.default.css'
// import '@selectize/selectize/dist/js/selectize.min.js'
import 'flowbite/dist/flowbite.min.js'

import $ from 'jquery';

window.$ = $;
window.read_more = read_more;
window.jstree_init = jstree_init;

$(function () {

    dropdown();
    hideAlert();
    // scrollTopError();

    jstree_init();

    $('.selectize').each(function () {
        const dataMaxItems = $(this).data('max-items');
        $(this).selectize({
            plugins: ["clear_button"],
            delimiter: " - ",
            persist: false,
            maxItems: dataMaxItems,
        });
    });

    $('.selectize-input').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500');

    $(document).on("reinitializeJstree", function () {
        $('.jstree').jstree('destroy');
        setTimeout(function() {
            jstree_init();
        }, 1);
    });

    $(document).on('click', '#toggleSidebar', function() {
        const sidebar = $('.sidebar');
        sidebar.toggleClass('');
    });

});

function jstree_init() {
    $('.jstree').jstree({
        "core" : {
            "themes" : {
                "variant" : "large",
                "responsive": false,
                "stripes": true,
            },
            "check_callback" : true,
        },
        "types": {
            "default": {
                "icon": false
            },
        },
        "plugins" : ["contextmenu", "state", "search", "types", "search"],
        "contextmenu": {
            "items": function (node) {
                if (node.data.contextmenu == 'curriculum_template' && node.children.length === 0) {
                    var menu = {
                        'delete': {
                            'label': 'Delete',
                            'action': function (data) {
                                const template_id = node.data.template_id;
                                window.location = `?action=delete&id=${template_id}`
                            }
                        }
                    };
                    return menu;
                }
            }
        }
    }).on('ready.jstree', function() {
        $(this).jstree('open_all');
    });

}

function read_more(elem) {
    $(document).on('click', elem, function() {
        const $prevElement = $(this).prev();
        $prevElement.toggleClass('hidden');

        const readMoreEllipsis = $(this).prevAll('.read-more-ellipsis').first();

        if (!$prevElement.hasClass('hidden')) {
            readMoreEllipsis.addClass('hidden');
            $(this).text('Read Less');
        } else {
            readMoreEllipsis.removeClass('hidden');
            $(this).text('Read More');
        }
    });
}

function scrollTopError() {
    $('#form-button').on('click', () => {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    })
}

function hideAlert() {
    $(document).on('click', '#close-alert', function() {
        $('#alert').fadeOut();
    });
}

function dropdown() {
    $(document).on('click', '#dropdown-button', function() {
        $('.dropdown').not($(this).next('.dropdown')).addClass('hidden');
        $(this).next('.dropdown').removeClass('hidden').css({
            position: 'absolute',
            inset: '0px auto auto 0px',
            margin: '0px',
            transform: 'translate3d(-140.5px, 34px, 0px)'
        });
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#dropdown-button, .dropdown').length) {
            $('.dropdown').addClass('hidden');
        }
    });
}

multi_select();

function multi_select() {
    let selected_card = 0;

    $(document).on('click', '.multi-select', function(e) {
        const checkbox = $(this);
        const state = checkbox.prop('checked');
        const container = checkbox.closest('.card-parent');

        selected_card += state ? 1 : -1;

        checkbox.prop('checked', state);
        container.toggleClass('border-sky-500', state);

        $('.multi-select-actions').toggleClass('hidden', selected_card === 0);
    });

    $(document).on('click', '#toggle-action', function() {
        $('.card-parent').removeClass('border-sky-500');
        $('.multi-select-actions').addClass('hidden');
        selected_card = 0;
    });
}

Livewire.on('leaving', (data) => {
    if(data[0].has_saved) {
        const result = window.confirm("This page is asking you to confirm that you want to leave — saved informations you’ve entered may not be saved.");
        if(result) {
            window.location.href= data[0].route;
        }
    } else {
        window.location.href= data[0].route;
    }

});
