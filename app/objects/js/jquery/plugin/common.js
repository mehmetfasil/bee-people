(function($){$.fn.ctype=function(type){var alpha="/[A-ZİĞÜŞÇa-zığüşç]/gm";var alphaLower="/[a-zğüşçı]/gm";var alphaUpper="/[A-ZİĞÜŞÇ]/gm";var numeric="/[0-9]/";var alphaNumeric="/[A-ZİĞÜŞÇa-zığüşç0-9]/gm";var string="";type==null?type="numeric":"";$(this).keypress(function(e){var keyCode=$.browser.msie?e.keyCode:e.which;string=String.fromCharCode(keyCode);if(keyCode!=8&&keyCode!=9){switch(type){case"numeric":if(string.search(numeric)==-1)return false;break;case"alpha":if(string.search(alpha)==-1)return false;break;case"alphaNumeric":if(string.search(alphaNumeric)==-1)return false;break;case"alphaLower":if(string.search(alphaLower)==-1)return false;break;case"alphaUpper":if(string.search(alphaUpper)==-1)return false;break;}}});}})($)