window.search = function(tag) {
    var text = tag.replace('#', '');
    $("#search-input").val(text);
    $("#search-input").keyup();
}