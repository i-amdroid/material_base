(function ($) {
  $(document).ready(function () {

    //form-item focused
    $('.form-item > *').focus(function () {
      $(this).parent().addClass('focused');
    });
    $('.form-item > *').blur(function () {
      $(this).parent().removeClass('focused');
    });

    //header-bg-image navbar-fixed
    var header = $('#header');
    var headerHeight = header.outerHeight();
    //var headerBg = $('.header-bg-video.navbar-fixed #header-bg');
    var headerBg = $('.navbar-fixed #header-bg');
    var navbar = $('.navbar-fixed #navbar');
    var headerBgHeight = headerHeight - 192;

    $(document).scroll(function (e) {
      var scrollPercent = (headerBgHeight - window.scrollY) / headerBgHeight;
      if (scrollPercent >= 0) {
        headerBg.css('opacity', scrollPercent);
      }
      if (window.scrollY > headerHeight - 128) {
        navbar.addClass('navbar-bg');
      }
      else {
        navbar.removeClass('navbar-bg');
      }
      if (window.scrollY > headerHeight - 64) {
        navbar.addClass('navbar-shadow');
      }
      else {
        navbar.removeClass('navbar-shadow');
      }
    });

    // drawer
    $('#navbar-menu-toggle').click(function (e) {
      $('body').addClass('drawer-open');
      e.stopPropagation();
      e.preventDefault();
    });

    $('#drawer-overlay').click(function (e) {
      $('body').removeClass('drawer-open');
    });

    $('#drawer-menu-close').click(function (e) {
      $('body').removeClass('drawer-open');
      e.stopPropagation();
      e.preventDefault();
    });

    // jquery touch events https://github.com/benmajor/jQuery-Touch-Events
    $('#drawer').swipeleft(function () {
      $('body').removeClass('drawer-open');
    });

    $('#drawer-overlay').swipeleft(function () {
      $('body').removeClass('drawer-open');
    });

    // smooth scroll for internal links
    var fixedHeight = 24;
    if ($('body').hasClass('navbar-fixed')) {
      fixedHeight += 64;
    }
    if ($('body').hasClass('toolbar-vertical')) {
      fixedHeight += 40;
    }
    if ($('body').hasClass('toolbar-horizontal')) {
      fixedHeight += 40;
      if ($('body').hasClass('toolbar-tray-open')) {
        fixedHeight += 40;
      }
    }

    $('a[href^="#"]:not([href="#"])').on('click', function (e) {
      e.preventDefault();
      var target = this.hash;
      var $target = $(target);
      $('html, body').stop().animate({
        'scrollTop': $target.offset().top - fixedHeight
      }, 600, 'swing', function (e) {
        if (history.pushState) {
          history.pushState(null, null, target);
        }
        else {
          window.location.hash = target;
        }
      });
    });

    var pathname = window.location.pathname;
    $('a[href^="' + pathname + '#"]').on('click', function (e) {
      e.preventDefault();
      var target = this.hash;
      var $target = $(target);
      $('html, body').stop().animate({
        'scrollTop': $target.offset().top - fixedHeight
      }, 600, 'swing', function (e) {
        if (history.pushState) {
          history.pushState(null, null, target);
        }
        else {
          window.location.hash = target;
        }
      });
    });

    // collapsible panels
    $('.collapsible-toggle').click(function () {
      var target = '#'.concat($(this).attr('data-target'));
      $(target).slideToggle('fast');
      $(this).toggleClass('expanded');
    });

    // tootips
    $('.tooltip').hover(function () {
      $(this).addClass('tooltip-expanded');
    }, function() {
      $(this).removeClass('tooltip-expanded');
    });

    // dropdown menus
    $('.menu-dropdown-toggle').click(function (e) {
      $(this).removeClass('tooltip-expanded');
      $(this).find('.tooltip').removeClass('tooltip-expanded');
      var target = '#'.concat($(this).attr('data-target'));
      $(target).slideToggle('fast');
      $('.menu-dropdown:not(' + target + ')').slideUp('fast');
      e.stopPropagation();
      e.preventDefault();
    });

    $(document).click(function () {
      $('.menu-dropdown').slideUp('fast');
    });

  });
}(jQuery));
