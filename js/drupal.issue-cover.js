(function ($) {

    Drupal.behaviors.issueCover = {
      attach: function(context, settings) {
        $('.newspaper-base-issue-cover', context).once('issue-cover', function() {
          new Drupal.issueCover($(this));
        });
      }
    };

    Drupal.issueCover = function($block) {
      this.$block = $block;

      this.$issues = $block.find('.issues');

      // Keep track of if there are no more events in either direction.
      this.nextMaxReached = this.$issues.find('.current').next('li').length == 0 ? true : false;
      if (this.nextMaxReached) {
        this.$block.addClass('next-max-reached');
      }
      this.previousMaxReached = this.$issues.find('.current').prev('li').length == 0 ? true : false;
      if (this.previousMaxReached) {
        this.$block.addClass('previous-max-reached');
      }

      // Set up and delegate click handlers for next and previous.
      $('.navigation a.next', $block).click($.proxy(this.go, this, 'next'));
      $('.navigation a.previous', $block).click($.proxy(this.go, this, 'prev'));
      this.$issues.on('click', 'a', $.proxy(this.handleClick, this));
      this.setPrevNext();
    }

    Drupal.issueCover.prototype.go = function(direction, e) {
      e.preventDefault();
      e.stopPropagation();

      var $current = this.$issues.find('.current');

      // Do nothing if there's no sibling.
      if ($current[direction]('li').length == 0) {
        return false;
      }

      // Load up new content if next item doesn't have a sibling.
      if ($current[direction]('li')[direction]('li').length == 0) {

        this.$block.addClass('working');
        $.ajax({
          type: 'GET',
          url: Drupal.settings.basePath + 'newspaper_base/issue',
          data: {
            tid: $current[direction]('li').attr('data-tid'),
            direction: direction
          },
          dataType: 'html',
          success: $.proxy(function(data, textStatus, jqXHR) {
            this.handleRequestIssuesSuccess(data, textStatus, jqXHR);
          }, this),
          error: $.proxy(this, 'handleRequestIssuesError')
        });
      }

      // @todo: setup proper animations for scrolling. For now only sets current
      // class.
      $current.removeClass('current')[direction]('li').addClass('current');
      this.setPrevNext();
    }

    Drupal.issueCover.prototype.handleRequestIssuesSuccess = function(data, textStatus, jqXHR) {
      var response = $.parseJSON(data);

      var insert = response.direction == 'previous' ? 'prepend' : 'append';
      var klass = response.direction == 'previous' ? 'first' : 'last';

      if (!response.item) {
        this[response.direction + 'MaxReached'] = true;
        this.$block.addClass(response.direction + '-max-reached');
      }
      else {
        console.log(this.$issues.find(klass));
        this.$issues.find('.' + klass).removeClass(klass);
        this.$issues[insert]($(response.item).addClass(klass));
        this.setPrevNext();
      }

      this.$block.removeClass('working');
    }

    Drupal.issueCover.prototype.handleRequestIssuesError = function(jqXHR, textStatus, errorThrown) {
    }

    Drupal.issueCover.prototype.setPrevNext = function() {
      var $current = this.$issues.find('.current');
      this.$issues.find('li').removeClass('next prev');
      $current.prev().addClass('prev');
      $current.next().addClass('next');
      this.$block.toggleClass('at-first', this.$issues.find('.current.first').length > 0);
      this.$block.toggleClass('at-last', this.$issues.find('.current.last').length > 0);
    }

    Drupal.issueCover.prototype.handleClick = function(e) {
      $el = $(e.currentTarget).closest('li');
      if ($el.hasClass('current')) {
        return true;
      }
      e.preventDefault();
      e.stopPropagation();
      if ($el.hasClass('prev')) { this.go('prev', e); }
      if ($el.hasClass('next')) { this.go('next', e); }
      return false;
    }

})(jQuery);
