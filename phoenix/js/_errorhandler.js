

window.onerror = function( msg, url, linenumber ){
	var error = document.createElement( 'div' );
	$( error ).html( msg ).appendTo( 'body' ).hide();
	Coala.Warm( 'errorhandler', {
			'msg': msg,
			'url': url,
			'linenumber': linenumber
		} );
};
