/*
 * Galleria Classic Theme v. 1.5 2010-10-28
 * http://galleria.aino.se
 *
 * Copyright (c) 2010, Aino
 * Licensed under the MIT license.
 */

(function($) {

Galleria.addTheme({
	name: 'classic',
	//author: 'Galleria',
	version: '1.5',
	//css: 'galleria.classic.css',
    defaults: {
        transition: 'slide',
        thumb_crop: 'height',
        max_scale_ratio: '1',
        clicknext: false, 
        popupLinks: false, 
		// set this to false if you want to show the caption all the time:
        _toggle_info: false
    },
    init: function(options) {

        // cache some stuff
        var toggle   = this.$('image-nav-left,image-nav-right,counter'),
            info     = this.$('info-text'),
            click    = Galleria.TOUCH ? 'touchstart' : 'click';
        
        // show loader & counter with opacity
        this.$('loader,counter').show().css('opacity',.4)

        // some stuff for non-touch browsers
        if (! Galleria.TOUCH ) {
            
            // fade thumbnails
            this.$('thumbnails').children().hover(function() {
                $(this).not('.active').children().stop().fadeTo(100, 1);
            }, function() {
                $(this).not('.active').children().stop().fadeTo(400, .6);
            });
            
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
            this.addIdleState( this.get('counter'), { opacity:0 });
        }
        
	info.show();

        
        // bind some stuff
        this.bind(Galleria.THUMBNAIL, function(e) {
            $(e.thumbTarget).parent(':not(.active)').children().css('opacity',.6)
        });
        
        this.bind(Galleria.LOADSTART, function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, .4);
            }
            
            this.$('info').toggle( this.hasInfo() );
            //this.$('info').show();
            
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity',.6);
        });
        
        this.bind(Galleria.LOADFINISH, function(e) {
            this.$('loader').fadeOut(200);
        });
    }
});

})(jQuery);
