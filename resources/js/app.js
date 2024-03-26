import './bootstrap'
import 'jstree'
import 'flowbite/dist/flowbite.min.js'
import ApexCharts from 'apexcharts'

import $ from 'jquery';

window.$ = $;
window.read_more = read_more;
window.jstree_init = jstree_init;
window.ApexCharts = ApexCharts;

$(function () {

    dropdown();
    multi_select();

    generate_color();

    $(document).on("reinitializeJstree", function () {
        $('.jstree').jstree('destroy');
        setTimeout(function() {
            jstree_init();
        }, 1);
    });

    $(document).on('click', '#toggleSidebar', function() {
        $('.sidebar').toggleClass('-translate-x-full')
    });

    $('.random-bg').each(function() {
        generate_color(this);
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

function generate_color(element) {
    $.ajax({
        url: 'https://x-colors.yurace.pro/api/random/all?type=dark',
        method: 'GET',
        success: function(response) {
            $(element).css('background-color', response.hex);
        }
    });
}

function get_size(size) {
    if(size >= 320 && size <= 768 ) {
        size = 'sm';
    } else if(size > 768 && size <= 1280) {
        size = 'md';
    } else if(size > 1280 && size <= 1535) {
        size = 'lg';
    } else if(size >= 1536) {
        size = 'xl';
    }

    return size;
}

function screenSize() {
    let size;
    size = get_size($(window).width());

    Livewire.dispatch('screen', [size]);
    $(window).resize(function() {
        size = get_size($(window).width());
        Livewire.dispatch('screen', [size]);
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

$(document).on('livewire:initialized', () => {
    screenSize();
    jstree_init();
});

Livewire.on('initPaginate', () => {
    screenSize();
});
