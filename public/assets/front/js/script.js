/*-----------------------------------------------------------
 * Template Name    : Multigig - Multivendor Freelancer Marketplace
 * Author           : KreativDev
 * File Description : This file contains the JavaScript for the actual template, this
                      is the file you need to edit to change the functionality of the
                      template.
 *------------------------------------------------------------ */

!(function ($) {
    "use strict";

    /*============================================
        Sticky header
    ============================================*/
    $(window).on("scroll", function () {
        var header = $(".header-area");
        // If window scroll down .is-sticky class will added to header
        if ($(window).scrollTop() >= 400) {
            header.addClass("is-sticky");
        } else {
            header.removeClass("is-sticky");
        }
    });

    /*============================================
        Mobile menu
    ============================================*/
    var mobileMenu = function () {
        // Variables
        var body = $("body"),
            mainNavbar = $(".main-navbar"),
            mobileNavbar = $(".mobile-menu"),
            cloneInto = $(".mobile-menu-wrapper"),
            cloneItem = $(".mobile-item"),
            menuToggler = $(".menu-toggler"),
            offCanvasMenu = $("#offcanvasMenu"),
            backdrop;

        // Function to initialize backdrop
        var initializeBackdrop = function () {
            backdrop = document.createElement('div');
            backdrop.className = 'menu-backdrop';
            backdrop.onclick = function () {
                menuToggler.removeClass("active");
                body.removeClass("mobile-menu-active");
                backdrop.remove();
            };
            document.body.appendChild(backdrop);
        };

        // Event handler for menu toggler
        menuToggler.on("click", function () {
            $(this).toggleClass("active");
            body.toggleClass("mobile-menu-active");

            if (body.hasClass("mobile-menu-active")) {
                initializeBackdrop();
            } else {
                $('.menu-backdrop').remove();
            }
        });

        // Clone items into mobile menu wrapper
        mainNavbar.find(cloneItem).clone(true).appendTo(cloneInto);

        if (offCanvasMenu.length) {
            body.find(offCanvasMenu).clone(true).appendTo(cloneInto);
        }

        // Event handling for mobile menu items
        mobileNavbar.find("li").each(function () {
            var toggleBtn = $(this).children(".toggle");
            toggleBtn.on("click", function () {
                $(this)
                    .parent("li")
                    .children("ul")
                    .stop(true, true)
                    .slideToggle(350);
                $(this).parent("li").toggleClass("show");
            });
        });

        // Function to check browser width in real-time
        var checkBreakpoint = function () {
            var winWidth = window.innerWidth;
            if (winWidth <= 1199) {
                mainNavbar.addClass("hide");
                mobileNavbar.removeClass("hide");
            } else {
                mainNavbar.removeClass("hide");
                mobileNavbar.addClass("hide");
                $('.menu-backdrop').remove();
            }
        };

        // Initial breakpoint check
        checkBreakpoint();

        // Event listener for window resize
        $(window).on('resize', function () {
            checkBreakpoint();
        });
    };
    // Call the mobileMenu function
    mobileMenu();

    var getHeaderHeight = function () {
        var headerNext = $(".header-next");
        var header = headerNext.prev(".header-area");
        var headerHeight = header.height();

        headerNext.css({
            "margin-top": headerHeight
        })
    }
    getHeaderHeight();

    $(window).on('resize', function () {
        getHeaderHeight();
    });

    // Category Megamenu
    $(document).ready(function () {
        // Function to handle submenu behavior
        function handleSubMenu() {
            var submenu = $(this).find('.sub-menu');
            var windowWidth = $(window).width();
            var submenuWidth = submenu.outerWidth();
            var menuItemPosition = $(this).offset().left;
            var menuItemPositionRight = windowWidth - menuItemPosition - $(this).outerWidth();

            // Close other open sub-menus
            $('.sub-menu.open').not(submenu).removeClass('open');

            if (windowWidth - menuItemPosition >= submenuWidth) {
                submenu.css('left', menuItemPosition);
            } else {
                submenu.css('left', 'unset');
                submenu.css('right', menuItemPositionRight);
            }

            // Toggle the 'open' class on the submenu
            submenu.toggleClass('open');
        }

        // Handle click outside to close open sub-menu
        $(document).on('click', function (event) {
            var target = $(event.target);

            // Check if the click is outside of .sub-menu and .sub-menu-item
            if (!target.closest('.sub-menu').length && !target.closest('.sub-menu-item').length) {
                $('.sub-menu.open').removeClass('open');
            }
        });

        // Toggle between hover and click events based on device width
        if ($(window).width() < 992) {
            // For small screens, use click event
            $('.categories-menu-nav .sub-menu-item').on('click', handleSubMenu);
        } else {
            // For larger screens, use hover events
            $('.categories-menu-nav .sub-menu-item').hover(
                handleSubMenu,
                function () {
                    // Mouse out, remove the 'open' class from the submenu
                    $(this).find('.sub-menu').toggleClass('open', false);
                }
            );
        }

        // Update the events on window resize
        $(window).on('resize', function () {
            if ($(window).width() < 992) {
                // For small screens, use click event
                $('.categories-menu-nav .sub-menu-item').off('hover').on('click', handleSubMenu);
            } else {
                // For larger screens, use hover events
                $('.categories-menu-nav .sub-menu-item').off('click').hover(
                    handleSubMenu,
                    function () {
                        // Mouse out, remove the 'open' class from the submenu
                        $(this).find('.sub-menu').toggleClass('open', false);
                    }
                );
            }
        });

    });

    // Category scroller
    $(document).ready(function () {
        var menus = $(".categories-menu-nav .sub-menu-item a");
        var scrollRightArrow = $(".categories-menu-nav .right-arrow");
        var scrollLeftArrow = $(".categories-menu-nav .left-arrow");
        var menuList = $(".categories-menu-nav ul.categories");
        var arrowContainers = $(".categories-menu-nav .left-arrow, .categories-menu-nav .right-arrow");

        // Function to remove "active" class from all menus
        var removeActiveClasses = () => menus.removeClass("active");

        // Function to update scroll buttons based on position
        var manageScrollButtons = () => {
            var isLeftVisible = menuList.scrollLeft() >= 20;
            var isRightVisible = menuList.scrollLeft() < menuList.prop("scrollWidth") - menuList.width() - 20;

            arrowContainers.filter(".left-arrow").toggleClass("active", isLeftVisible);
            arrowContainers.filter(".right-arrow").toggleClass("active", isRightVisible);
        };

        // Add "active" class to clicked tab and remove from others
        menus.on("click", function (event) {
            removeActiveClasses();
            $(this).addClass("active");
        });

        // Scroll the tab list on arrow click
        var scrollMenus = (direction) => {
            var newScrollLeft = menuList.scrollLeft() + direction * 200;
            menuList.animate({
                scrollLeft: newScrollLeft
            }, 100, manageScrollButtons);
        };

        // Scroll the tab list to the right
        scrollRightArrow.on("click", () => scrollMenus(1));

        // Scroll the tab list to the left
        scrollLeftArrow.on("click", () => scrollMenus(-1));

        // Listen to scroll event to manage buttons
        menuList.on("scroll", manageScrollButtons);

        // Adding the dragging functionality
        var drag = false;
        var dragging = (e) => {
            if (!drag) return;
            menuList.addClass("dragging");
            menuList.scrollLeft(menuList.scrollLeft() - e.originalEvent.movementX);
        };

        menuList.on("mousedown", function () {
            drag = true;
            menuList.on("mousemove", dragging);
        });

        $(document).on("mouseup", function () {
            drag = false;
            menuList.off("mousemove", dragging);
            menuList.removeClass("dragging");
        });
    });

    /*============================================
        Navlink active class
    ============================================*/
    var a = $("#mainMenu .nav-link"),
        c = window.location;

    for (var i = 0; i < a.length; i++) {
        const el = a[i];

        if (el.href == c) {
            el.classList.add("active");
        }
    }

    /*============================================
        Magnific popup
    ============================================*/
    $('.btn-search').magnificPopup({
        removalDelay: 500,
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true
    });
    // Youtube Popup
    $(".youtube-popup, .video-popup").magnificPopup({
        disableOn: 300,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    })
    // Service Details Image Popup
    $('.service-slider-image').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
    // Image Popup
    $('.image-popup').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    /*============================================
        Read More Btn Toggle
    ============================================*/
    $(".read-more-btn").on("click", function () {
        $(this).prev().toggleClass('show');

        if ($(this).prev().hasClass("show")) {
            $(this).text(readLess);
        } else {
            $(this).text(readMore);
        }
    })

    /*============================================
        Image to background image
    ============================================*/
    var bgImage = $(".bg-img")
    bgImage.each(function () {
        var el = $(this),
            src = el.attr("data-bg-img");

        el.css({
            "background-image": "url(" + src + ")",
            "background-repeat": "no-repeat"
        });
    });

    /*============================================
        Tabs mouse hover animation
    ============================================*/
    $("[data-hover='fancyHover']").mouseHover();

    /*============================================
        Sliders
    ============================================*/
    // Sponsor Slider
    var sponsorSlider = new Swiper(".sponsor-slider", {
        speed: 1200,
        loop: true,
        spaceBetween: 30,
        slidesPerView: 4,
        autoplay: {
            delay: 3000,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1
            },
            400: {
                slidesPerView: 2
            },
            768: {
                slidesPerView: 3
            },
            1200: {
                slidesPerView: 4
            }
        }
    });

    // Testimonial Slider
    var testimonialSlider1 = new Swiper("#testimonial-slider-1", {
        speed: 1200,
        slidesPerView: 1,
        grabCursor: true,
        grid: {
            rows: 2,
        },

        // Pagination bullets
        pagination: {
            el: "#testimonial-slider-1-pagination",
            clickable: true,
        },

        on: {
            init: function () {
                var pagination = $('#testimonial-slider-1-pagination'),
                    paginationLength = $('#testimonial-slider-1-pagination span'),
                    currentSlide = 1,
                    totalSlide = paginationLength.length.toString().padStart(2, '0');
                pagination.append(`
                        <div class="fraction">
                            <span class='min'></span>
                            <span>/</span>
                            <span class='max'></span>
                        </div>
                    `)

                pagination.find(".min").text('0' + currentSlide)
                pagination.find(".max").text(totalSlide)
            },
        }
    });
    if ($("#testimonial-slider-2 .swiper-slide").length > 0) {
        var testimonialSlider2 = new Swiper("#testimonial-slider-2", {
            speed: 1200,
            spaceBetween: 30,
            slidesPerView: 3,
            autoplay: {
                delay: 3000,
            },

            // Pagination bullets
            pagination: {
                el: "#testimonial-slider-2-pagination",
                clickable: true,
            },

            breakpoints: {
                320: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 2
                },
                1200: {
                    slidesPerView: 3
                }
            },

            on: {
                init: function () {
                    var pagination = $('#testimonial-slider-2-pagination'),
                        paginationLength = $('#testimonial-slider-2-pagination span'),
                        currentSlide = 1,
                        totalSlide = paginationLength.length.toString().padStart(2, '0');
                    pagination.append(`
                            <div class="fraction">
                                <span class='min'></span>
                                <span>/</span>
                                <span class='max'></span>
                            </div>
                        `)

                    pagination.find(".min").text('0' + currentSlide)
                    pagination.find(".max").text(totalSlide)
                },
            }
        });
    }
    var testimonialSlider3 = new Swiper("#testimonial-slider-3", {
        speed: 1200,
        spaceBetween: 30,
        slidesPerView: 3,
        autoplay: {
            delay: 3000,
        },

        // Pagination bullets
        pagination: {
            el: "#testimonial-slider-3-pagination",
            clickable: true,
        },

        breakpoints: {
            320: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3
            }
        },

        on: {
            init: function () {
                var pagination = $('#testimonial-slider-3-pagination'),
                    paginationLength = $('#testimonial-slider-3-pagination span'),
                    currentSlide = 1,
                    totalSlide = paginationLength.length.toString().padStart(2, '0');
                pagination.append(`
                        <div class="fraction">
                            <span class='min'></span>
                            <span>/</span>
                            <span class='max'></span>
                        </div>
                    `)

                pagination.find(".min").text('0' + currentSlide)
                pagination.find(".max").text(totalSlide)
            },
        }
    });
    // Service Details SLider
    $('.gigs-big-slider').slick({
        dots: false,
        arrows: true,
        infinite: false,
        autoplaySpeed: 1500,
        asNavFor: '.gigs-thumbs-slider',
        slidesToShow: 1,
        slidesToScroll: 1,
        rtl: langDir == 1 ? true : false,
        prevArrow: '<div class="prev"><i class="far fa-angle-left"></i></div>',
        nextArrow: '<div class="next"><i class="far fa-angle-right"></i></div>',
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false
            }
        }]
    });
    $('.gigs-thumbs-slider').slick({
        dots: false,
        arrows: false,
        infinite: false,
        autoplaySpeed: 1500,
        focusOnSelect: true,
        asNavFor: '.gigs-big-slider',
        slidesToShow: 5,
        slidesToScroll: 1,
        rtl: langDir == 1 ? true : false,
        responsive: [{
            breakpoint: 767,
            settings: {
                slidesToShow: 3
            }
        }]
    });

    // Stop slider autoplay
    $(document).ready(function () {

        if ($(".swiper").length) {
            var mySwiper = document.querySelector(".swiper").swiper;

            $(".swiper").mouseenter(function () {
                mySwiper.autoplay.stop();
            });

            $(".swiper").mouseleave(function () {
                mySwiper.autoplay.start();
            });
        }
    });

    /*============================================
        Go to top
    ============================================*/
    $(".go-top-btn").on("click", function (e) {
        $("html, body").animate({
            scrollTop: 0,
        }, 0);
    });

    /*============================================
        Lazyload image
    ============================================*/
    var lazyLoad = function () {
        window.lazySizesConfig = window.lazySizesConfig || {};
        window.lazySizesConfig.loadMode = 2;
        lazySizesConfig.preloadAfterLoad = true;

        var lazyContainer = $(".lazy-container");

        if (lazyContainer.children(".lazyloaded")) {
            lazyContainer.addClass("lazy-active")
        } else {
            lazyContainer.removeClass("lazy-active")
        }
    }

    /*============================================
        Nice select
    ============================================*/
    $(".niceselect").niceSelect();

    var selectList = $(".nice-select .list")
    $(".nice-select .list").each(function () {
        var list = $(this).children();
        if (list.length > 5) {
            $(this).css({
                "height": "160px",
                "overflow-y": "scroll"
            })
        }
    })

    /*============================================
        Footer date
    ============================================*/
    var date = new Date().getFullYear();
    $("#footerDate").text(date);

    /*============================================
      Search post by category
    ============================================*/
    $('.blog-category').on('click', function (e) {
        e.preventDefault();

        let value = $(this).data('category_slug');

        $('#categoryKey').val(value);
        $('#submitBtn').trigger('click');
    });

    /*============================================
      Disqus init
    ============================================*/
    if (typeof shortName !== 'undefined') {
        let d = document,
            s = d.createElement('script');
        s.src = `https://${shortName}.disqus.com/embed.js`;
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    }

    /*============================================
        datepicker init
    ============================================*/
    $('.datepicker').datepicker({
        autoclose: true
    });

    /*============================================
      Timepicker init
    ============================================*/
    $('.timepicker').timepicker();

    /*============================================
      Initialize bootstrap dataTable
    ============================================*/
    var dataTable = function () {
        var userDataTable = $("#user-datatable");

        if (userDataTable.length) {
            userDataTable.DataTable({
                ordering: false,
            })
        }
    }

    /*============================================
        Toggle List
    ============================================*/
    $("[data-toggle-list]").each(function (i) {
        var list = $(this).children();
        var listShow = $(this).data("toggle-show");
        var listShowBtn = $(this).next("[data-toggle-btn]");

        if (list.length > listShow) {
            listShowBtn.show()
            list.slice(listShow).toggle(300);

            listShowBtn.on("click", function () {
                list.slice(listShow).slideToggle(300);
                $(this).text($(this).text() === showLess + " -" ? showMore + " +" : showLess + " -")
            })
        } else {
            listShowBtn.hide()
        }
    })

    /*============================================
      Add user email for subscription
    ============================================*/
    $('.subscription-form').on('submit', function (event) {
        event.preventDefault();

        let formURL = $(this).attr('action');
        let formMethod = $(this).attr('method');

        let formData = new FormData($(this)[0]);

        $.ajax({
            url: formURL,
            method: formMethod,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('input[name="email_id"]').val('');

                toastr['success'](response.success);
            },
            error: function (errorData) {
                toastr['error'](errorData.responseJSON.error.email_id[0]);
            }
        });
    });

    /*============================================
      Uploaded image preview
    ============================================*/
    $('.upload').on('change', function (event) {
        let file = event.target.files[0];
        let reader = new FileReader();

        reader.onload = function (e) {
            $('.user-photo').attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    });

    /*============================================
      Floating whatsapp
    ============================================*/
    if (whatsappStatus == 1) {
        $('.whatsapp-btn').floatingWhatsApp({
            phone: whatsappNumber,
            popupMessage: whatsappPopupMessage,
            showPopup: whatsappPopupStatus == 1 ? true : false,
            headerTitle: whatsappHeaderTitle,
            position: 'right'
        });
    }

    /*============================================
      Tinymce initialization
    ============================================*/
    $(".summernote").each(function (i) {
        tinymce.init({
            selector: '.summernote',
            plugins: 'autolink charmap emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            promotion: false,
            mergetags_list: [{
                value: 'First.Name',
                title: 'First Name'
            },
            {
                value: 'Email',
                title: 'Email'
            },
            ]
        });
    });

    /*============================================
      Uploaded file progress bar and file name preview
    ============================================*/
    $('.custom-file-input').on('change', function (e) {
        let file = e.target.files[0];
        let fileName = e.target.files[0].name;

        let fd = new FormData();
        fd.append('attachment', file);

        $.ajax({
            xhr: function () {
                let xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener('progress', function (ele) {
                    if (ele.lengthComputable) {
                        let percentage = ((ele.loaded / ele.total) * 100);
                        $('.progress').removeClass('d-none');
                        $('.progress-bar').css('width', percentage + '%');
                        $('.progress-bar').html(Math.round(percentage) + '%');

                        if (Math.round(percentage) === 100) {
                            $('.progress-bar').addClass('bg-success');
                            $('#attachment-info').text(fileName);
                        }
                    }
                }, false);

                return xhr;
            },
            url: $(this).data('url'),
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function (res) {

            }
        });
    });

    /*============================================
      Format date & time for announcement popup
    ============================================*/
    $('.offer-timer').each(function () {
        let $this = $(this);

        let date = new Date($this.data('end_date'));
        let year = parseInt(new Intl.DateTimeFormat('en', {
            year: 'numeric'
        }).format(date));
        let month = parseInt(new Intl.DateTimeFormat('en', {
            month: 'numeric'
        }).format(date));
        let day = parseInt(new Intl.DateTimeFormat('en', {
            day: '2-digit'
        }).format(date));

        let time = $this.data('end_time');
        time = time.split(':');
        let hour = parseInt(time[0]);
        let minute = parseInt(time[1]);

        $this.syotimer({
            year: year,
            month: month,
            day: day,
            hour: hour,
            minute: minute
        });
    });

    /*============================================
      Count total view of an advertisement
    ============================================*/
    function adView(id) {
        let url = `${baseURL}/advertisement/${id}/count-view`;

        let data = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        $.post(url, data, function (response) {
            if ('success' in response) {

            } else {

            }
        });
    }

    /*============================================
        Tooltip
    ============================================*/
    var tooltipTriggerList = [].slice.call($('[data-tooltip="tooltip"]'))

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    /*============================================
        Offcanvas menu
    ============================================*/
    var widgetOffcanvas = function () {
        // Variables
        var body = $("body"),
            offcanvasToggler = $("[data-toggle='widgetOffcanvas']"),
            offcanvasClose = $("[data-dismiss='widgetOffcanvas']"),
            backdrop,
            initializeBackDrop = function () {
                backdrop = document.createElement('div');
                backdrop.className = 'offcanvas-backdrop';
                backdrop.onclick = function hideOffCanvas() {
                    offcanvasToggler.removeClass("active"),
                        body.removeClass("offcanvas-active"),
                        backdrop.remove();
                };
                document.body.appendChild(backdrop);
            };

        offcanvasToggler.on("click", function () {
            $(this).toggleClass("active");
            body.toggleClass("offcanvas-active");
            initializeBackDrop();
            if (!body.hasClass("offcanvas-active")) {
                $('.offcanvas-backdrop').remove();
            }
        })

        offcanvasClose.on("click", function () {
            offcanvasToggler.removeClass("active");
            body.removeClass("offcanvas-active");
            if (!body.hasClass("offcanvas-active")) {
                $('.offcanvas-backdrop').remove();
            }
        })
    }
    widgetOffcanvas();

    /*============================================
      Document on ready
    ============================================*/
    $(document).ready(function () {
        lazyLoad();
        dataTable();

        // Set up click event for the copy button
        $('#copyBtn').on('click', function () {
            // Get the text to copy
            var textToCopy = $('#textToCopy');
            // Create a temporary textarea element
            var tempTextarea = $('<textarea>');
            // Set the value of the textarea to the text to copy
            tempTextarea.val(textToCopy.text());
            // Set the textarea style to make it hidden
            tempTextarea.css({
                position: 'absolute',
                left: '-1000px',
                top: '-1000px',
                opacity: '0'
            });
            // Append the textarea to the body
            $('body').append(tempTextarea);
            // Select the text in the textarea
            tempTextarea.select();
            // Copy the selected text to the clipboard
            document.execCommand('copy');
            toastr['success']("Copied");
        });

        // Show password message
        var myInput = $(".helper-form i");
        var message = $(".helper-text");
        // When the user clicks on the password field, show the message box
        myInput.on("mouseenter", function () {
            message.css("display", "block");
        });
        // When the user clicks outside of the password field, hide the message box
        myInput.on("mouseout", function () {
            message.css("display", "none");
        });
    })

})(jQuery);

/*============================================
    Popup
============================================*/
function appearPopup($this) {
    let closedPopups = [];

    if (sessionStorage.getItem('closedPopups')) {
        closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
    }

    // if the popup is not in closedPopups Array
    if (closedPopups.indexOf($this.data('popup_id')) == -1) {
        $('#' + $this.attr('id')).show();

        let popupDelay = $this.data('popup_delay');

        setTimeout(function () {
            jQuery.magnificPopup.open({
                items: {
                    src: '#' + $this.attr('id')
                },
                type: 'inline',
                callbacks: {
                    afterClose: function () {
                        // after the popup is closed, store it in the sessionStorage & show next popup
                        closedPopups.push($this.data('popup_id'));
                        sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));

                        if ($this.next('.popup-wrapper').length > 0) {
                            appearPopup($this.next('.popup-wrapper'));
                        }
                    }
                }
            }, 0);
        }, popupDelay);
    } else {
        if ($this.next('.popup-wrapper').length > 0) {
            appearPopup($this.next('.popup-wrapper'));
        }
    }
}

$(window).on("load", function () {
    const delay = 1000;
    /*============================================
        Preloader
    ============================================*/
    $("#preLoader").delay(delay).fadeOut();

    /*============================================
        Aos animation
    ============================================*/
    var aosAnimation = function () {
        AOS.init({
            easing: "ease",
            duration: 1200,
            once: true,
            offset: 60,
            disable: "mobile"
        });
    }
    if ($("#preLoader")) {
        setTimeout(() => {
            aosAnimation()
        }, delay);
    } else {
        aosAnimation();
    }

    /*============================================
      Initialize Popup
    ============================================*/
    if ($('.popup-wrapper').length > 0) {
        let $firstPopup = $('.popup-wrapper').eq(0);
        appearPopup($firstPopup);
    }

    // scroll to bottom
    if ($('.message-list').length > 0) {
        $('.message-list')[0].scrollTop = $('.message-list')[0].scrollHeight;
    }
});

// update services in the wishlist
$('body').on('click', '.wishlist-link', function (event) {
    event.preventDefault();

    let _this = $(this);

    let url = $(this).attr('href');
    let element = $(this).data('element_type');

    let data = {
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.post(url, data, function (response) {
        if ('login_route' in response) {
            window.location = response.login_route;
        } else {
            _this.children().toggleClass('added-in-wishlist');
            if (response.status == 'Added') {
                _this.children("span").text(rmvBtnTxt);
                _this.attr('data-bs-original-title', remove_from_wishlist);
                toastr['success'](response.message);
            } else {
                _this.children("span").text(addBtnTxt);
                _this.attr('data-bs-original-title', save_to_wishlist);
                toastr['error'](response.message);
            }
            _this.blur();
        }
    });
});

// completed btn onchange event
$('.completeBtn').on('change', function (e) {
    e.preventDefault();
    $(".request-loader").addClass("show");

    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        buttons: {
            confirm: {
                text: 'Yes, Mark it as complete',
                className: 'btn btn-success'
            },
            cancel: {
                visible: true,
                className: 'btn btn-danger'
            }
        }
    }).then((Delete) => {
        if (Delete) {
            $(this).parent(".completeForm").submit();
        } else {
            swal.close();
            $(".request-loader").removeClass("show");
        }
    });
});
// completed btn onchange event end
