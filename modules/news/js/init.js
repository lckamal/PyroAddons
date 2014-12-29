$(function(){
    $(".news-ticker").bootstrapNews({
        newsPerPage: 4,
        autoplay: true,
        pauseOnHover: true,
        direction: 'down',
        newsTickerInterval: 5000,
        onToDo: function () {
            //console.log(this);
        }
    });
  });