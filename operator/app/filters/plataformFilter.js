'use strict';

app
  .filter('splitText', function($filter) {
    return function(input, delimiter, index) {
      if (input == null) {
        return "";
      }
      var input = input.split(delimiter)[index];
      return input;
    };
  })
