var Profile = {
    CurrentValues: {},
    Init: function () {
        if ( $( '.accountmenu' ).length ) {
            $( '.accountmenu a:eq(0)' ).click( function () {
                axslt( false, 'call:user.modal.settings', function() {
                    var $modal = $( this ).filter( 'div' );
                    $modal.prependTo( 'body' ).modal();
                    //tabbing
                    $modal.find( '.tablist > li > a' ).click( function() {
                        var tabname = $( this ).parent().attr( 'id' ).split( '_' )[1];
                        
                        if ( !$( this ).parent().hasClass( 'selected' ) ) {
                            $( this ).parent().addClass( 'selected' )
                                    .siblings( '.selected' ).removeClass( 'selected' );
                            $modal.find( 'div#tab_' + tabname ).show().siblings( 'div.tab' ).hide();
                            $modal.center();
                            if ( tabname == 'email' ) {
                                var $email = $( '#tab_email input[name=email]' );
                                if ( $email.val() == '' ) {
                                    $.get( 'users/' + User, {}, function( res ) {
                                        $email.val( $( res ).find( 'email' ).text() );
                                    } );
                                }
                            }
                        }
                        return false;
                    } );
                    //password change
                    $modal.find( '#tab_email a.save' ).click( function() {
                        var email = $( '#tab_email input[name=email]' ).val();
                        if ( Kamibu.ValidEmail( email ) ) {
                            $.post( 'user/update', { email: email } );
                            $modal.jqmHide();
                        }
                        else {
                            alert( 'Η μορφή του email που έγραψες είναι λάθος' );
                        }
                        return false;
                    } );
                    $modal.find( '#tab_password a.save' ).click( function() {
                        var oldpass = $modal.find( 'input[name="oldpassword"]' ).val();
                        var newpass = $modal.find( 'input[name="newpassword"]' ).val();
                        var newpass2 = $modal.find( 'input[name="newpassword2"]' ).val();
                        if ( newpass != newpass2 ) {
                            alert( 'Η επιβεβαίωση του νέου κωδικού απέτυχε, ξαναγράψε τον νέο κωδικό σωστά και στα δύο πεδία' );
                            return false;
                        }
                        $.post( 'user/update', { oldpass: oldpass, newpass: newpass }, function( res ) {
                            if ( $( res ).find( 'operation result' ).text() == 'SUCCESS' ) {
                                alert( 'Ο κωδικός άλλαξε με επιτυχία!' );
                                $modal.jqmHide();
                            }
                            else {
                                alert( 'Ο παλιός κωδικός που πληκτρολόγησες είναι λάθος' );
                            }
                        } );
                        return false;
                    } );
                } );
                return false;
            } );
            $( '.accountmenu a:eq(1)' ).click( function () {
                document.body.style.cursor = 'pointer';
                $.post( 'session/delete', {}, function () {
                    Kamibu.Go( 'login' );
                } );
                return false;
            } );
            
            Profile.PrepareInlineEditables();
            $( '#useravatar' ).click( function() { 
                $.get( 'albums/' + $( '.maininfo .username' ).text(), {}, function( res ) {
                    var egoalbumid = $( res ).find( 'album[egoalbum=yes]' ).attr( 'id' );
                    axslt( $.get( 'albums/' + egoalbumid ), 'call:user.avatar.edit', function() {
                        var $modal = $( this ).filter( 'div' );
                        $modal.prependTo( 'body' ).modal();
                        $modal.find( 'a.noimage' ).attr( 'href', 'albums/' + egoalbumid );
                        $modal.find( 'ul li' ).click( function( event ) {
                            $.post( 'album/update', { albumid: egoalbumid, mainimageid: $( this ).attr( 'id' ).split( '_' )[1] } );
                            $( '#useravatar' ).find( 'img' ).attr( 'src', $( this ).find( 'img' ).attr( 'src' ) );
                            $modal.jqmHide().remove();
                        } );
                    } );
                } );
            } );
        }
        else {
            $( '#useravatar' ).click( function() { 
                window.location.href = 'photos/' + $( '.maininfo .username' ).text();
            } );
        }
        if ( $( '.friendship' ).length ) {
            $( '.friendship a' ).click( function( $form ) {
                return function() {
                    $.post( $form[ 0 ].action, $form.serialize(), function( res ) {
                        method = $( res ).find( 'operation' ).attr( 'method' );
                        friendid = $( res ).find( 'friend' ).attr( 'id' );
                        if ( method == 'delete' ) {
                            $( '.friendship' )[ 0 ].action = 'friendship/create';
                            $( '.friendship a' )[ 0 ].className = 'love linkbutton';
                            $( '.friendship a' )[ 0 ].innerHTML = '<strong>+</strong> Προσθήκη φίλου';
                            $( '.friendship a' )[ 0 ].title = 'Προσθήκη φίλου';
                        }
                        else {
                            $( '.friendship' )[ 0 ].action = 'friendship/delete';
                            $( '.friendship a' )[ 0 ].innerHTML = '<strong>&#9829;</strong><strong class="delete">/</strong>Φίλος';
                            $( '.friendship a' )[ 0 ].title = 'Διαγραφή φίλου';
                        }
                    } );
                    return false;
                };
            }( $( '.friendship' ) ) );
        }
        
        // User Interests
        $( '.interestitems li .delete' ).live( 'click', function(){
            Profile.Interests.Remove( $( this ).parent() );
        });
        $( '.userinterests li div span.add' ).click( function(){
            Profile.Interests.StartAdd( $( this ).closest( 'li' ) );
        });

        Comment.Init();
    },
    Interests: {
        Remove: function( li ){
            $.post( 'interest/delete', {
                id: li.attr( 'id' ).split( '_' )[ 1 ]
            }, function(){
                $( li ).prev().addClass( 'last' ).end().remove();

            });
        },
        StartAdd: function( li ){
            $( li ).append( $( '<input type="text" name="tagitem" />' ) ).children( 'input' ).focus().blur( function(){
                Profile.Interests.CloseAdd( $( this ).closest( 'li' ) );        
            }).keypress( function( e ){
                if( e.which == 13 ){
                    Profile.Interests.Create( $( this ).val(), $( this ).closest( 'li' ).attr( 'class' ));
                    Profile.Interests.CloseAdd( li );
                }
            });
        },
        CloseAdd: function( li ){
            $( li ).children( 'input' ).remove();
        },
        Create: function( text, type ){
            $.post( 'interest/create', {
                type: type,
                text: text
            }, function( data ){
                var id = $( data ).find( 'tag' ).attr( 'id' );
                var text = $( data ).find( 'tag' ).text();
                $( '.' + type ).find( '.last' ).removeClass( 'last' );
                var tagitem = $( '<li class="last" id="tag_' + id + '"></li>' )
                    .text( text ).append( '<span class="delete">&#215;</span>' );
                $( '.' + type ).children( 'ul' ).append( tagitem );
            });
        }
    },
    PrepareMoodPicker: function() {
        $( '.mood' ).addClass( 'editable' );
        $( '.mood .activemood' ).click( function() {
            axslt( $.get( 'moods' ), 'call:user.mood.edit', function() {
                $activemood = $( '.mood > .activemood' );
                $activemood.hide();
                $moodpicker = $( this ).filter( 'div' );
                $moodpicker.appendTo( '.mood' );
                $activetile = $moodpicker.find( '#mood_' + $activemood.attr( 'id' ).split( '_' )[1] );
                $activetile.closest( 'li' ).addClass( 'activemood' );
                $moodpicker.find( 'ul li:not( .activemood )' ).click( function() {
                    var $newactivemood = $( this ).find( '.moodtile' );
                    var moodid = $newactivemood.attr( 'id' ).split( '_' )[1];
                    $activemood.replaceWith( $newactivemood );
                    $newactivemood.attr( 'id', 'active' + $newactivemood.attr( 'id' ) ).addClass( 'activemood' );
                    $moodpicker.hide().remove();
                    $.post( 'user/update', { 'moodid': moodid } );
                    Profile.PrepareMoodPicker();
                } );
                $moodpicker.find( '.modalclose, ul li.activemood' ).click( function() {
                    $moodpicker.hide().remove();
                    $activemood.show();
                } );
            }, { 'gender': Profile.CurrentValues[ 'gender' ] } );
        } );
    },
    PrepareInlineEditables: function() {
        Profile.PrepareMoodPicker();
        Profile.PopulateEditables();
        $( '#age' ).addClass( 'editable' );
        Calendar.Init( 'age', function( year, month, day ) {
            var now = new Date();
            if( now.getFullYear() - 61 >= year ){
                year = now.getFullYear() - 61;
            }
            if( now.getFullYear() - 9 <= year ){
                year = now.getFullYear() - 9;
            }
            if ( now.getMonth() + 1 >= month && now.getDate() >= day ) {
                document.getElementById( 'age' ).innerHTML = now.getFullYear() - year;
            }
            else {
                document.getElementById( 'age' ).innerHTML = now.getFullYear() - year - 1;
            }
            $.post( '?resource=user&method=update', { dob: year + '-' + month + '-' + day } );
        }, 'Ημερομηνία γέννησης' );
        if ( $( 'li.aboutme > span' ).hasClass( 'notshown' ) ) {
            $( 'li.aboutme > span' ).text( 'Να μην εμφανίζεται' );
        }
        $( 'li.aboutme > span' ).addClass( 'editable' ).click( function() {
            axslt( false, 'call:user.modal.aboutme', function() {
                var $modal = $( this ).filter( 'div' );
                $modal.prependTo( 'body' ).modal();
                var text = '';
                if ( !$( 'li.aboutme > span' ).hasClass( 'notshown' ) ) {
                    text = $( 'li.aboutme > span' ).text();
                }
                $modal.find( 'textarea.aboutme' ).val( text ).focus();
                //$modal.find( 'textarea.aboutme' ).val( text ).focus();
                $modal.find( 'a.save' ).click( function() {
                    var text = $modal.find( 'textarea.aboutme' ).val();
                    $( 'li.aboutme > span' ).text( text ).removeClass( 'notshown' );
                    $.post( 'user/update', { 'aboutme': text } );
                    $modal.jqmHide();
                    return false;
                } );
                $modal.find( 'a.linebutton' ).click( function() {
                    $.post( 'user/update', { aboutme: '' } );
                    $( 'li.aboutme > span' ).text( 'Να μην εμφανίζεται' ).addClass( 'notshown' );
                    $modal.jqmHide();
                    return false;
                } );
            } );
            return false;
        } );
        var location_id = $( '.asl .location span' ).attr( 'id' ).split( '_' )[1];
        if ( location_id == '' ) {
            $( '.asl .location span' ).text( 'Όρισε περιοχή' ).addClass( 'notshown' );
        }
        $( '.asl .location span' ).addClass( 'editable' ).click( function() {
            //TODO: don't re-open the modal
            //      default value if empty
            //      update CurrentValues
            axslt( false, 'call:user.modal.location', function() {
                $modal = $( this ).filter( 'div' );
                $modal.prependTo( 'body' ).modal();
                var $select = $modal.find( 'select.location' );
                axslt( $.get( 'places' ), 'call:user.modal.location.options', function() {
                    var location_id = $( '.asl .location span' ).attr( 'id' ).split( '_' )[1];
                    if ( location_id == '' ) {
                        location_id = -1;
                    }
                    $select.empty();
                    $select.append( $( this ).filter( 'option' ) ).val( location_id );
                    var $notshown = $( '<option value="-1">Να μην εμφανίζεται</option>' );
                    if ( location_id == -1 ) {
                        $select.prepend( $notshown );
                    }
                    else {
                        $select.append( $notshown );
                    }
                    $select.val( location_id );
                    $select.change( function() {
                        $.post( 'user/update', { placeid: $select.val() } );
                        if ( $select.val() == -1 ) {
                            $( '.asl .location span' ).text( 'Όρισε περιοχή' ).addClass( 'notshown' ).attr( 'id', 'location_' );
                        }
                        else {
                            $( '.asl .location span' ).text( $select.find( 'option[value=' + $select.val() + ']' ).text() )
                                .removeClass( 'notshown' ).attr( 'id', 'location_' + $select.val() );
                        }
                        
                        $modal.jqmHide();
                        return false;
                    } );
                } );
            } );
            return false;
        } );
        if ( $( 'div.slogan' ).hasClass( 'notshown' ) ) {
            $( 'div.slogan' ).text( 'Όρισε σλόγκαν' );
        }
        $( 'div.slogan' ).addClass( 'editable' ).click( function() {
            axslt( false, 'call:user.modal.slogan', function () { 
                $modal = $( node_strip( this ) );
                $modal.appendTo( 'body' ).modal();
                if ( !$( 'div.slogan' ).hasClass( 'notshown' ) ) {
                    $modal.find( 'input' ).val( $( 'div.slogan' ).text() );
                }
                $modal.find( '.save' ).click( function () {
                    var slogan = $modal.find( 'input' ).val();
                    $modal.jqmHide().remove();
                    $( 'div.slogan' ).text( slogan ).removeClass( 'notshown' );
                    $.post( 'user/update', { slogan: slogan } );
                    return false;
                } );
                $modal.find( '.linebutton' ).click( function () {
                    $modal.jqmHide().remove();
                    $.post( 'user/update', { slogan: '' } );
                    $( 'div.slogan' ).text( 'Όρισε σλόγκαν' ).addClass( 'notshown' );
                    return false;
                } );
            } );
            return false;
        } );
        //var $sloganinput = $( '<input/>' ).val( $( 'div.slogan' ).text() );
        //Kamibu.EditableTextElement( $( 'div.slogan' ).get( 0 ), 'Όρισε σλόγκαν', function( text ) { alert( text ); } );
        //$( 'div.slogan' ).text( '' ).append( $sloganinput );
        if ( window.ActiveXObject ) {
            $( '.editable select' ).css( { opacity: 0, width: 'auto' } );
        }
    },
    UpdatableFields: { 'gender': '.asl .gender',
                   'smoker': 'li.smoker > span',
                   'drinker': 'li.drinker > span',
                   'relationship': 'li.relationship > span',
                   'politics': 'li.politics > span',
                   'religion': 'li.religion > span',
                   'sexualorientation': 'li.sexualorientation > span',
                   'eyecolor': 'li.eyecolor > span',
                   'haircolor': 'li.haircolor > span',
                   'height': 'li.height > span',
                   'weight': 'li.weight > span'
                 },
    PopulateEditables: function() {
        UserDetails.Init();
        var field;
        for ( field in Profile.UpdatableFields ) {            
            Profile.MakeEditable( $( Profile.UpdatableFields[ field ] ), field );
        }
    },
    MakeEditable: function( element, field ) {
        element.addClass( 'editable' );
        var oldselect = $( element ).find( 'select.dropdown' );
        if ( field == 'height' || field == 'weight' ) {
            Profile.CurrentValues[ field ] = oldselect.val() || '-3';
        }
        else {
            Profile.CurrentValues[ field ] = oldselect.val() || '-';
        }
        oldselect.empty();
        var select = $( '<select />' ).addClass( 'dropdown' );
        
        oldselect.replaceWith( select );
        Profile.PopulateSelect( element, field );
        
        $( select ).change( function() {
            Profile.CurrentValues[ field ] = $( this ).val();
            var span = $( this ).siblings().filter( 'span' );
            Profile.UpdateField( span, field );
            
            if ( field == 'gender' ) {
                var ifield;
                for ( ifield in Profile.UpdatableFields ) { 
                    if ( ifield != 'gender' ) {
                        Profile.PopulateSelect( $( Profile.UpdatableFields[ ifield ] ), ifield );
                    }
                }
            }
            var postvars = {};
            postvars[ field ] = $( this ).val();
            $.post( 'user/update', postvars );
        } );
        select.appendTo( element );//.css( 'display', 'block' );
    },
    PopulateSelect: function( element, field ) {
        var map = UserDetails.GetMap( field, Profile.CurrentValues[ 'gender' ] );
        var nbsp = String.fromCharCode( 160 );
        var select = $( element ).find( 'select' );
        select.empty();
        if ( typeof( map[ -2 ] ) != 'undefined' ) {
            $( '<option />' ).attr( 'value', -2 ).text( nbsp + map[ -2 ] + nbsp).appendTo( select );
        }
        for ( key in map ) {
            if ( key != -2 ) {
                $( '<option />' ).attr( 'value', key ).text( nbsp + map[ key ] + nbsp).appendTo( select );
            }
        }
        select.val( Profile.CurrentValues[ field ] );
        Profile.UpdateField( $( element ).find( 'span' ), field );
    },
    UpdateField: function( span, field ) {
        var text = UserDetails.GetString( field, Profile.CurrentValues[ field ], Profile.CurrentValues[ 'gender' ] );
        $( span ).removeClass( 'notshown' ).text( text );
        if ( Profile.CurrentValues[ field ] == '-' ) {
            span.addClass( 'notshown' );
        }
    }
};
