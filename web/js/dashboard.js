autoPlayCoub = false;
autoPlayTimer = null;

// TASK FORM
function actionCostChange() {
    var l = ($('#tasksocial-action_cost').val() * $('#tasksocial-action').val());
    if (taskData.oldLikes) {
        l = l - taskData.oldLikes;
    }
    $('#tasksocial-likes').val( (l>0 ? l : ''));
    return true;
}

function actionTypeChange() {
    $('.dushboard-addTask form').attr('class', 'type-' + $(this).val());
    var min = taskData.minCost[taskData.social][$(this).val()];
    var actionCost = $('#tasksocial-action_cost');
    actionCost.attr('min', min);
    actionCost.attr('placeholder', actionCost.attr('data-min') + ' ' + min);
    return true;
}
// TASK LIST

function hoverLoadGifOn() {
    var obj = $(this);
    var previewItem  = obj.find('.previewItem');

    if (obj.data('data-tm-out')) {
        clearTimeout(obj.data('data-tm-out'));
    }
    obj.data('data-tm-in', setTimeout(function () {
        previewItem.eq(0).hide();
        previewItem.eq(1).show();
        previewItem.eq(1).get(0).play();
    }, 1000));
}

function hoverLoadGifOff() {
    var obj = $(this);
    var previewItem  = obj.find('.previewItem');

    if (obj.data('data-tm-in')) {
        clearTimeout(obj.data('data-tm-in'));
    }
    obj.data('data-tm-out', setTimeout(function () {
        previewItem.eq(1).get(0).pause();
        previewItem.eq(1).hide();
        previewItem.eq(0).show();
    }, 200));
}

function eachCountDown() {
    var obj = $(this);
    var startTime = parseInt(obj.attr('data-countdown'));
    if (startTime > 0) {
        obj.css('position', 'relative');
        obj.append('<span class="countdown">' + startTime + '</span>');
        var objC = obj.find('.countdown');
        //scaleCountDown(objC);
        var intTime = setInterval(function () {
            if (startTime <= 0) {
                clearInterval(intTime);
                obj.removeClass('disabled');
                objC.remove();
            }
            else {
                startTime--;
                objC.text(startTime);
                //scaleCountDown(objC)
            }
        }, 1000);
    }
}

function scaleCountDown(obj) {
    obj.stop();
    obj.css({transform: 'scale(0,1)', scale: '0.1', opacity: 1});
    obj.animate(
        {scale: '1.0'},
        {
            duration: 700,
            step: function (now, fx) {
                $(this).css('transform', 'scale(' + now + ',' + now + ')');
            },
            done: function () {
                obj.animate({opacity: 0}, 200);
            }
        }
    );
}

var playId;
// Earn
function frameEventListen() {
    window.addEventListener('message', function (e) {
        console.log('********', e.data,  e);
        if (e.data === 'playStarted') {
            progressRunBlock($('div.col-block[data-id='+playId+']'));
        }
        //else if (e.data == 'loopOccured') {
        //    activateTaskBlock(getPlayBlock());
        //}
        else if (e.data === 'playPaused') {
            progressPauseBlock($('div.col-block.isActive'));
        }
    });
}

function stopAutoPlay() {
    if (autoPlayCoub && autoPlayTimer) {
        clearTimeout(autoPlayTimer);
        autoPlayTimer = null;
    }
}

function previewClick() {
    stopAutoPlay();
    previewDisactivate();
    var parentObj = $(this).parent();
    var blockObj = parentObj.parent();
    playId = blockObj.data('id');
    console.log('previewClick set ', playId);

    // var previewObj = parentObj.find('.frame_block_preview');
    blockObj.addClass('isLoading');
    if (blockObj.hasClass('isNew')) {
        blockObj.removeClass('isNew');
        parentObj.append('<iframe src="' + parentObj.attr('data-iframe') + '" allowfullscreen="true" frameborder="0" data-id="'+playId+'"></iframe>');

        var progressObj = blockObj.find('.progress');
        if (progressObj.length) {
            progressObj.show();
            progressObj.find('.progress-bar').width('5%');
            progressObj.data('timeLeft', blockObj.attr('data-duration'));
        }

        setTimeout(function () {
            if (!blockObj.hasClass('isActive')) {
                var iframeObj = parentObj.find('iframe');
                iframeObj.get(0).contentWindow.postMessage('play', '*');
                console.log('* PLAY');
            }
        }, 2000);
    }
    else {
        var iframeObj = parentObj.find('iframe');
        // iframeObj.show();
        iframeObj.get(0).contentWindow.postMessage('play', '*');
    }

    // previewObj.hide();
}


function previewDisactivate() {

    var activeObj = $('div.col-block.isActive');
    if (!activeObj.length) return;

    // Хоть этот метод и вызывается когда срабатывает playPaused, но только к тому мементу эта функция успевает удалить класс isActive
    progressPauseBlock(activeObj);

    var iframeObj = activeObj.find('iframe');
    if (!iframeObj.length) return;
    iframeObj.each(function( i ) {
        this.contentWindow.postMessage('stop', '*');
    });
    playId = 0;
    // var previewObj = activeObj.find('.frame_block_preview');
    // if (!previewObj.length) return;
    // previewObj.show();
}


function activateTaskBlock(activeObj) {
    activeObj.addClass('isViewed');
    if (activeObj.hasClass('type_view')) {
        var tmpFunct = $.proxy(getFreeLike, activeObj.find('.getFreeLike').get(0));
        tmpFunct();
    }
}


function progressRunBlock(activeObj) {

    var duration = parseInt(activeObj.attr('data-duration'));
    activeObj.addClass('isActive');
    activeObj.removeClass('isLoading');


    if (autoPlayCoub) {
        stopAutoPlay();
        autoPlayTimer = setTimeout(function () {
            if (!autoPlayCoub) return;
            var nextCoub = activeObj.next('.social_coub');
            if (!nextCoub.length) {
                nextCoub = activeObj.parent().children(":first.social_coub");
            }
            nextCoub = nextCoub.find('.playBtnPreview');
            console.log('autoPlay', nextCoub);

            var tmpFunct = $.proxy(previewClick, nextCoub);
            tmpFunct();

            console.log('+++++', nextCoub);
        }, duration*2*1000);
    }

    var progressObj = activeObj.find('.progress');
    if (!progressObj.length) return;
    if (progressObj.data('intervalRes')) {
        return;
    }

    var timeLeft = parseInt(progressObj.data('timeLeft'));
    if (timeLeft<=0) {
        return;
    }
    var progressBarObj =  progressObj.find('.progress-bar');

    progressObj.data('intervalRes', setInterval(function() {
        if (timeLeft<=0) {
            clearInterval(progressObj.data('intervalRes'));
            progressObj.removeData('intervalRes');
            progressObj.hide();
            activateTaskBlock(activeObj);
        }
        timeLeft -= 1;
        progressObj.data('timeLeft', timeLeft);
        var v = parseInt( (duration - timeLeft) * 100 / duration);
        progressBarObj.width(v + '%').text(timeLeft + ' сек.');

    }, 1000));
    //console.log('SET INTERVAL', progressObj.data('intervalRes'), progressObj);
}

function progressPauseBlock(activeObj) {
    if (!activeObj.length) return;
    activeObj.removeClass('isActive');
    // var blockObj = $('div.col-block.isActive');
    stopAutoPlay();
    var progressObj = activeObj.find('.progress');
    if (!progressObj.length) return;
    if (progressObj.data('intervalRes')) {
        //console.log('CLEAR INTERVAL', progressObj.data('intervalRes'), progressObj);
        clearInterval(progressObj.data('intervalRes'));
        progressObj.removeData('intervalRes');
    }
    else {
    }

    if (progressObj.data('timeLeft')>0) {
        progressObj.find('.progress-bar').text('Нужно закончить просмотр!');
    }
    else {
        progressObj.hide();
    }
    //blockObj.addClass('isViewed');
    //if (blockObj.hasClass('type_view')) {
    //    $.proxy(getFreeLike, blockObj.find('.getFreeLike').get(0))
    //}
}


function getFreeLike() {

    var objA = $(this);
    var objBlock = objA.parents('.social_coub');

    if ( objBlock.data('runFunction') ) {
        return false;
    }
    objBlock.data('runFunction', true);

    $.ajax({
        url: objA.attr('data-url'),
        dataType: 'json'
    }).done(function( data ) {
        console.log('getFreeLike : RES: ',  (data['status']), data);
        if (data['status']) {
            objBlock.addClass('isComplete');

            var animObj = objBlock.find('.animateSuccessLike');
            animObj.css('display', 'block');
            animObj.animate({'font-size': "130px", opacity: 1}, 1200, 'linear', function() {
                animObj.animate({'font-size': "150px", opacity: 0}, 600, 'linear', function() {
                    animObj.css('display', 'none');
                });
            });

            $('.userLikes').text( ( parseInt(data['action_cost']) + parseInt($('.userLikes').text()) ));
        }
        else {
            objBlock.addClass('isError');
            alert(data['mess']);
        }
    }).fail(function(res) {
        console.error(res.responseText);
        alert( "Ошибка. Попробуйте позднее." );
    });
    return false;
}

function ignoreLike() {
    var objA = $(this);
    $.ajax({
        url: objA.attr('data-url'),
        dataType: 'json'
    }).done(function( data ) {
        console.log( (data['status']), data);
        if (data['status']) {
            objA.parent().addClass('isIgnore');
        }
        else {
            objA.parent().addClass('isError');
            alert(data['mess']);
        }
    });
    return false;
}


function showMorePages() {
    console.log('showMorePages');
    var btnObj = $(this);
    btnObj.html('Loading...');
    $.ajax({
        url: location.href,
        dataType: 'html',
        data: {idList: idList}
    }).done(function( data ) {
        btnObj.parent().parent().append(data);
        btnObj.parent().remove();
    });
}

function changeViewlist() {
    document.cookie = "view=" + $(this).attr('data-view');
    location.reload(true);
}


// BUY

function buyFormRecount() {
    var idLikes = 'inpt-likes';
    var idCost = 'inpt-cost';
    var idBonus = 'inpt-bonus';
    var idBonusDisc = 'inpt-disc';
    var idTotal = 'inpt-total';
    // buyConfig
    var currentInputObj = $(this);
    var isLikeInput = (currentInputObj.attr('id') == idLikes);
    var costLikes = 0;
    var costPrice = 0;
    if (isLikeInput) {
        costLikes = parseInt(currentInputObj.val());
        costPrice = Math.floor(parseInt(costLikes / buyConfig.curs));
        $('#' + idCost).val( (costPrice ? costPrice : 0));
    }
    else {
        costPrice = parseInt(currentInputObj.val());
        costLikes = parseInt(costPrice * buyConfig.curs)
        $('#' + idLikes).val( (costLikes ? costLikes : 0) );
    }
    var bonusP = 0;
    for (var i in buyConfig.pack) {
        if (i>=costLikes) {
            break;
        }
        bonusP = buyConfig.pack[i];
    }
    $('#' + idBonusDisc).val( (costLikes ? bonusP : 0) );
    var bonusLikes = Math.ceil(costLikes * bonusP / 100);
    $('#' + idBonus).val( (bonusLikes ? bonusLikes : 0) );
    $('#' + idTotal).val( (bonusLikes ? costLikes  + bonusLikes : 0) );

    if (!costLikes || costLikes<buyConfig.minLikes) {
        $('#ahtung').slideDown();
        $('#btnBuy').attr('disabled', 'disabled');
    }
    else {
        $('#ahtung').slideUp();
        $('#btnBuy').removeAttr('disabled');
    }
}

function buyLikes(likes) {
    likes = parseInt(likes);
    if (likes && buyConfig.minLikes<=likes) {
        location.href = '/userpay/' + likes;
    }
    return false;
}