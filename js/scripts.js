
function getWoeids()
{

    //populate woeid dropdown
    $.ajax({
        type: "GET",
        url: "functions.php?function=getwoeids",
        dataType: "json",

        success: function(data){

            var locationSelect = $('#woeids');
            $.each(data, function(){
                locationSelect.append(
                    $('<option></option>').val(this.woeid).html(this.name)
                );
            });

            $('#loading-locations').hide();
            $(locationSelect).show();
        }
    });


}

function getTrendsForLocation(woeid)
{
    $('#trends-loading').show();
    var resultTable = $('#results');
    var header = $('#location-name-header');

    $(resultTable).hide();
    $(header).html('For '+$("#woeids option:selected").text());
    $('.result-row').remove();
    $('#counter').html('0');
    $.ajax({
        type: "GET",
        url: "functions.php?function=gettrends&woeid="+woeid,
        dataType: "json",

        success: function(data){


            $.each(data[0].trends, function(){

                $(resultTable).append(
                    $('<tr/>',{
                        class: 'result-row'
                    }).append(
                            $('<td/>',{
                                html: '<a href="'+this.url+'" target="_BLANK" title="'+this.name+'">'+this.name+'</a>'
                            })
                        )/*.append(
                            $('<td/>',{
                                html: '<a href="'+this.url+'" target="_BLANK" title="'+this.query+'">'+this.query+'</a>'
                            })
                        )*/.append(
                            $('<td/>',{
                                html: '<a href="'+this.url+'" target="_BLANK" title="'+this.url+'">'+decodeURIComponent(this.url)+'</a>'
                            })
                        )
                );

            });
            $('#trends-loading').hide();
            $(resultTable).slideDown('slow');
            $('#counter').html($('.result-row').length);
        }
    });
}

function getTimelineForUser(username,nooftweets)
{
    var twitTable = $('#twitter-feed > tbody');
    $.ajax({
        type: "GET",
        url: "functions.php?function=gettimeline&username="+username+"&nooftweets="+nooftweets,
        dataType: "json",


        success: function(data){
            $(twitTable).empty();

            $.each(data, function(){
                $(twitTable).append(
                    $('<tr/>', {
                        style: 'border-bottom: 1px dotted gray;'
                    }).append(
                            $('<td/>').append(
                                $('<img/>',{
                                    title:   this.text,
                                    alt:     this.text,
                                    src:     this.user.profile_image_url,
                                    class:   "tweetimage"
                                })
                            )
                        ).append(
                            $('<td/>',{
                                class:   "tweet",
                                html:    parseTwitter(this.text)
                            })
                        )
                );
            });
        }
    });
}

function parseTwitter(text) {
    // Parse URIs
    text = text.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/, function(uri) {
        return uri.link(uri);
    });

    // Parse Twitter usernames
    text = text.replace(/[@]+[A-Za-z0-9-_]+/, function(u) {
        var username = u.replace("@","");
        return u.link("http://twitter.com/"+username);
    });

    // Parse Twitter hash tags
    text = text.replace(/[#]+[A-Za-z0-9-_]+/, function(t) {
        var tag = t.replace("#","%23");
        return t.link("http://www.twitter.com/"+tag);
    });
    return text;
}


$(document).ready(function() {

    $('#woeids').change(function(){
        var selectedWoeId = $(this).val();
        if(selectedWoeId != ''){
            getTrendsForLocation(selectedWoeId);
        }

    });

    $('#go-timeline-button').click(function(){
        if(!$('#prependedInput').val().length){
            alert("Please provide a Username");
        }else{
            getTimelineForUser($('#prependedInput').val(),10);
        }

    });

});