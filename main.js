var resultList = $("div#result-list");
var resultCounter = $("#results-found")
var inputUrl = $("#input-group-url input");

function syncFormEnable() {
    $('input[type=checkbox][data-enable]').each(function() {
        var checked = $(this).prop('checked');
        var toggleName = $(this).attr('data-enable');
        $('*[name=' + toggleName + ']').prop('disabled', !checked);
        if (checked)
            $('*[name=' + toggleName + ']').addClass('in');
        else
            $('*[name=' + toggleName + ']').removeClass('in');
    });
}

function onClickRun(e) {
    var serializedForm = $('form#cyos-form').serialize();
    resultList.empty();
    resultCounter.html("0");

    var uri = inputUrl.val();
    var apiUri = 'api.php?url=' + encodeURIComponent(uri) + '&' + serializedForm;

    $("a#api-uri").attr('href', apiUri);

    $.get(apiUri, function(data) {
        console.log(data);
        resultCounter.html($('item', data).size());
        var i = 1;
        $('item', data).each(function() {
            var itemDiv = $('<div class="panel panel-default"/>');
            var itemLink = $("link", this).text();
            itemDiv.append($("<div class='panel-heading'>")
                .append(i++ + ". ").append($('title', this)));
            itemDiv.append($("<div class='panel-body'>")
                .append($("<a>").append(itemLink).attr('href', itemLink))
                .append($("<br>"))
                .append($('date', this))
                .append($("<br>"))
                .append($("<span>").append($('author', this)))
                .append($("<br>"))
                .append($("<p>").append($('description', this))));
            resultList.append(itemDiv);
        });
    }).error(function(x) {
        alert(x.responseText);
    });
}

function setExampleUrl() {
    inputUrl.val($(this).attr('data-example-url'));
}


/*
 * Click handlers
 */
$("input").on('click', syncFormEnable);

$("#input-group-url button").on('click', onClickRun);

$("a[data-example-url]").on('click', setExampleUrl);

/*
 * Run once at load
 */
syncFormEnable();
