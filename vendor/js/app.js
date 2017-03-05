$(document).foundation();


$(document).ready(function(){
    $('.warningReveal').foundation('open');
});

//Variables and Arrays
var daysNumbers = ['M','T','W','H','F','S'];

var classSchedule = {
    classID: classes.length,
    className: 'Activity Period',
    color: '#f48342',
    allottedTimes: [[
        'T',
        1245,
        1415,
    ], [
        'H',
        1245,
        1415
    ]]
};
var currentSelection = [classSchedule];
classes.push(classSchedule);

addClass(classes.length-1, classSchedule.color);
var chosenClasses = [];
var classColors = ['#bfd7ff','#bdd3b8', '#fffdc1' , '#ffe6b2', '#ffafaf', '#d0c4ff', '#dbdbdb', '#a8ffcc'];


//Autocomplete Setup
var searchSelection = $('#searchType').find(":selected").val();
var options = {
    url: 'data/'+searchSelection+'.js',

    list: {
        match: {
            enabled: true
        },
        onKeyEnterEvent: function(){
            searchClasses();
        }
    },
    theme: "square"
};
$("#autocomplete").easyAutocomplete(options);

$('select').change(function(){
    var searchSelection = $('#searchType').find(":selected").val();
    var options = {
        url: 'data/'+searchSelection+'.js',

        list: {
            match: {
                enabled: true
            },
            onKeyEnterEvent: function(){
                searchClasses();
            }
        },
        theme: "square"
    };
    $("#autocomplete").easyAutocomplete(options);
});
/***FUNCTIONS***/

if (typeof queryClasses !== 'undefined') {
    loadSchedule(queryClasses);
}

function resetOptions(){
    $(".classOption").remove();
    var currentOptions = [];
}
//'<a class="classOption-time" target="_blank" href="http://ca.ratemyteachers.com/marianopolis-college/38444-s?q='+collegeClass.teacherFirstName+'+'+collegeClass.teacherLastName+'">'+collegeClass.teacherName+'</a>';
function createOptions(collegeClass, selected){
    var classBlock ='';
    if (selected == true){
        classBlock = '<div class="classOption-selected" id="classOption-ID-'+collegeClass.classID+'">';
    }else{
        classBlock = '<div class="classOption" id="classOption-ID-'+collegeClass.classID+'">';
    }
    classBlock += '<div class="expanded row"> <div class="large-8 classOption-left collapse small-6 columns">';
    classBlock += '<p class="classOption-time">'+ collegeClass.className+' / '+collegeClass.courseCode+' / '+collegeClass.sectionNumber+'</p>';
    classBlock += '<p class="classOption-time">'+collegeClass.teacherName+'</p>';
    classBlock += '<p class="classOption-time"><i class="fi-star"></i> '+collegeClass.teacherRating+'/5 | '+collegeClass.teacherNumberRatings+'</p>';
    classBlock += '</div><div class="large-4 classOption-right collapse small-6 columns">';

    collegeClass.allottedTimes.forEach(function(time){
        var startTime = String(time[1]);
        startTime = startTime.substr(0,startTime.length-2)+':'+startTime.substr(-2,2);
        var endTime = String(time[2]);
        endTime = endTime.substr(0,endTime.length-2)+':'+endTime.substr(-2,2);
        classBlock += '<p class="classOption-time">'+time[0]+': '                                     +startTime+'-'+endTime+'</p>';
    });
    $('.classOptions').append(classBlock);
    if (selected==true){
        $('#classOption-ID-'+collegeClass.classID).css("border-left", "solid 6px "+collegeClass.color);
    }
}

//Find classes that match search query
function searchClasses(){
    resetOptions();
    var searchQuery = $('#autocomplete').val();
    var searchOption = $('#searchType').find(":selected").val();
    if (searchQuery == 'COM'){
        classes.forEach(function(element){
            if (element.courseCode.charAt(0).match(/[a-z]/i)|| element.courseCode=="504-LBQ-03" || element.courseCode =="520-LAA-03"){
                createOptions(element);
            }
            // if (searchQuery=='COM' && element.courseCode){
            //
            // }
        });
    }else{
        classes.forEach(function(element){
            if (element[searchOption]==searchQuery){
                createOptions(element);
            }
            // if (searchQuery=='COM' && element.courseCode){
            //
            // }
        });
    }
}

//Find where each class block should go
function findScheduleBlock (element){
    var day = daysNumbers.indexOf(element[0]);

    var startTime =  element[1]-815;
    var startConstant = ((Math.floor(startTime/100))*2);
    var startNumber = startConstant+ (startTime-startConstant*50)/30;

    var endTime = element[2]-815;
    var endConstant = (Math.floor(endTime/100))*2;
    var endNumber = endConstant + ((endTime-endConstant*50)/30) - 1;

    var times = [day, startNumber,endNumber];
    return times;
}

//Add Class to schedule
function addClass(classID, classColor){
    var chosenClass = classes[classID];
    chosenClass.allottedTimes.forEach(function(element){
        var scheduleBlock = findScheduleBlock(element);
        var day = scheduleBlock[0];
        var startNumber = scheduleBlock[1];
        var endNumber = scheduleBlock[2];

        $('.c'+day+'.r'+startNumber).attr('rowspan', endNumber-startNumber+1);
        $('.c'+day+'.r'+startNumber).css('background-color', classColor);
        $('.c'+day+'.r'+startNumber).html('<p class="scheduleText">'+chosenClass.className+'</p>');

        for (i=startNumber+1; i<=endNumber; i++){
            $('.c'+day+'.r'+i).css('display', 'none');
        }
        // <p>'+chosenClass.teacherName+'</p><p>'+chosenClass.courseCode+'</p>');

    });
}

//Add class to schedule when clicked and remove it when clicked again
$(document).on('click','.classOption', function() {
    var classOptionID = parseInt($(this).attr('id').match(/\d/g).join(""));
    console.log(classOptionID);
    var overlap = scheduleOverlap(classOptionID);
    if (!overlap[0]) {
        $(this).css("border-left", "solid 6px"+classColors[0]);
        addClass(classOptionID, classColors[0]);
        classes[classOptionID].color = classColors[0];
        currentSelection.push(classes[classOptionID]);
        classColors.shift();
        $(this).addClass('classOption-selected');
        $(this).removeClass('classOption');
    }else{
        alert('Conflict between '+overlap[1].className+'-'+overlap[1].sectionNumber+' and '+overlap[2].className+'-'+overlap[2].sectionNumber);
    }
});

//Removes class from schedule
function deleteClass(id){
    //chosenClasses.splice(chosenClasses.indexOf(id),1);
    var classDeleted = classes[id];
    classDeleted.allottedTimes.forEach(function(element){
        var scheduleBlock = findScheduleBlock(element);
        var day = scheduleBlock[0];
        var startNumber = scheduleBlock[1];
        var endNumber = scheduleBlock[2];

        $('.c'+day+'.r'+startNumber).attr('rowspan', 0);
        $('.c'+day+'.r'+startNumber).css('background-color', 'white');
        $('.c'+day+'.r'+startNumber).html('');

        for(i=startNumber+1; i<=endNumber; i++){
            $('.c'+day+'.r'+i).css('display', 'table-cell');
        }
    })
}

//Add Class to officially selected list
function addClassOfficial(classOptionID, classColor){
    if (chosenClasses.indexOf(classOptionID) == -1){
        chosenClasses.push(classOptionID);
        classOptionID = parseInt(classOptionID);
        var chosenClass = classes[classOptionID];
        var chosenClassBlock = '<div style="border-left-color:'+classColor+'; "class="chosenClass chosenClassColor-'+classColor+'"><div class="row"><div class="large-9 small-9 columns">';
        chosenClassBlock += '<h6 class = "chosenClass-title">'+chosenClass.courseCode+'</h6>';
        chosenClassBlock += '</div><div style="text-align: right;" class="large-3 small-3 columns"><i class="chosenClass-change fi-pencil"> </i><i class=" chosenClass-delete fi-x"></i></div>';
        chosenClassBlock += '<div class="row"><div class="large-12 small-12 columns">';
        chosenClassBlock += '<p class="classOption-time">'+chosenClass.className;
        chosenClassBlock += ' | '+ chosenClass.teacherName +'</p>';
        chosenClassBlock += '</div></div></div>';
        $('.chosenClasses').append(chosenClassBlock);
        addClass(classOptionID);
    }
}

function saveClasses(classArray){
    var saveCode = '';

    classArray.forEach(function(element){
        if (element.classID != classes.length-1){
            saveCode += element.classID+'-';
        }
    });
    saveCode = saveCode.substr(0,saveCode.length-1);
    $('.saveReveal h4').remove();
    $('.saveReveal').append('<h4>Your Save Code is: '+saveCode);
    $('.saveReveal').foundation('open');
}

$(document).on('click','.saveButton', function(event) {
    event.preventDefault();
    saveClasses(currentSelection);
});

function loadSchedule (code){
    if (code.match(/[a-z]/i)){
        alert('Invalid Code Format');
    }
    classColors = ['#bfd7ff','#bdd3b8', '#fffdc1' , '#ffe6b2', '#ffafaf', '#d0c4ff', '#dbdbdb', '#a8ffcc'];

    currentSelection.forEach(function(element, index){
        if (element.classID!=classes.length-1){
            deleteClass(element.classID);
        }
    });

    currentSelection = currentSelection.splice(0, 1);

    var loadClasses = code.split('-');
    $('.classOption-selected').remove();
    $('.classOption').remove();
    loadClasses.forEach(function(element){
        var loadClass = classes[parseInt(element)];
        loadClass.color = classColors[0];
        classColors.splice(0,1);
        addClass(loadClass.classID, loadClass.color);
        createOptions(loadClass, true);
        currentSelection.push(loadClass);
    });

}
//Load schedule
$('.loadSchedule-input').keypress(function(event){
    if (event.which ==13){
        loadSchedule($('.loadSchedule-input').val());
    }
});

$('.loadButton').on('click', function(event){
    event.preventDefault();
    $('.loadReveal').foundation('open');
});

$('.loadQuery-button').on('click', function(event){
    event.preventDefault();
    loadSchedule($('.loadSchedule-input').val());
});


//Export Schedule
$('.exportButton').on('click', function(event){
    event.preventDefault();
    var currentClasses = currentSelection.splice(1,currentSelection.length);
    currentClasses.forEach(function(element){
        var exportClass = '';
        exportClass += '<li>';
        exportClass += element.courseCode+ ' -> ';
        exportClass += element.sectionNumber;
        exportClass += '</li>';
        $('.exportClasses').append(exportClass);
    });
    $('.exportReveal').foundation('open');

});
/***EVENTS***/
//Shows results for query when return key is pressed
$("#autocomplete").keypress(function(event) {
    if (event.which == 13){
        searchClasses();
    }
});

$(document).on('click','.eac-item', function(){
    searchClasses();
});

$(document).on('click','.search-magnifying-glass', function(){
    searchClasses();
});

$(document).on('keypress','.eac-item', function(event){
    if (event.which == 13){
        searchClasses();
    }
});


$(document).on('click','.classOption-selected', function() {
    var classOptionID = parseInt($(this).attr('id').match(/\d/g).join(""));
        $(this).removeClass('classOption-selected');
        $(this).addClass('classOption');
        deleteClass(classOptionID);
        currentSelection.filter(function(element){
           return element.classID != classOptionID;
        });
    classColors.push(classes[classOptionID].color);
        currentSelection.forEach(function (element, index) {
            if (element.classID == classOptionID) {
                currentSelection.splice(index, 1);
            }
        });
        $(this).css({
            "border-color": 'lightgray',
            "border-width": "1px",
            "border-style": "solid"
        });
});


function classOverlap(classID1, classID2){
    var class1 = classes[classID1];
    var class2 = classes[classID2];
    var overlap = false;
    class1.allottedTimes.forEach(function(dayTime1){
        class2.allottedTimes.forEach(function(dayTime2){
            if (dayTime1[0]==dayTime2[0] &&
                ((dayTime1[1]>=dayTime2[1] && dayTime1[1]<dayTime2[2])||
                (dayTime1[2]<=dayTime2[2])&& dayTime1[2]>dayTime2[1])){
                overlap = true;
            }
        })
    });
    return [overlap, class1, class2];
}

function scheduleOverlap (classID){
    var overClasses = [false];
    currentSelection.forEach(function(class2){
        var classOverlapArray = classOverlap(class2.classID, classID);
        if(classOverlapArray[0]){
            overClasses[0]= true;
            overClasses.push(classOverlapArray[1]);
            overClasses.push(classOverlapArray[2]);
        }
    });
    return overClasses;
}

$('.instructionButton').on('click', function(event){
    event.preventDefault();
    $('.revealInstructions').foundation('open');
});

$('.fb-share-button').on('click', function(event){
    $('.fb-share-button').attr('data-href', 'asdf');
    // event.preventDefault();
});

// $(document).on('click','.classOption', function(){
//     var classOptionID = parseInt($(this).attr('id').match(/\d/g).join(""));
//     if (chosenClasses.indexOf(classOptionID) == -1){
//         chosenClasses.push(classOptionID);
//         classOptionID = parseInt(classOptionID);
//         var chosenClass = classes[classOptionID];
//         var chosenClassBlock = '<div class="chosenClass"><div class="row"><div class="large-9 small-9 columns">';
//         chosenClassBlock += '<h6 class = "chosenClass-title">'+chosenClass.courseCode+'</h6>';
//         chosenClassBlock += '</div><div style="text-align: right;" class="large-3 small-3 columns"><i class="chosenClass-change fi-pencil"> </i><i class=" chosenClass-delete fi-x"></i></div>';
//         chosenClassBlock += '<div class="row"><div class="large-12 small-12 columns">';
//         chosenClassBlock += '<p class="classOption-time">'+chosenClass.className;
//         chosenClassBlock += ' | '+ chosenClass.teacherName +'</p>';
//         chosenClassBlock += '</div></div></div>';
//         $('.chosenClasses').append(chosenClassBlock);
//         addClass(classOptionID);
//     }
//
// });
