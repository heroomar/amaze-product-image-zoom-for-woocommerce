( function () {
	'use strict';

	const SELECTORS = [
		'.woocommerce-product-gallery__image',
		'.wc-block-product-gallery-large-image__wrapper'
	];

	const MAGNIFIER_CLASS = 'amaze-wpim-magnifier';
	const MOBILE_CLASS = 'amaze-wpim-magnifier--mobile';

	function isMobile() {
		return window.matchMedia( '(max-width: 767px)' ).matches;
	}

	function getImageFromContainer( container ) {
		return container.querySelector( 'img' );
	}

	function getSourceImage( image ) {
		if ( ! image ) {
			return null;
		}

		const source = image.getAttribute( 'data-large_image' ) || image.currentSrc || image.src;

		if ( ! source ) {
			return null;
		}

		return {
			url: source,
			width: parseInt( image.getAttribute( 'data-large_image_width' ), 10 ) || image.naturalWidth || image.width,
			height: parseInt( image.getAttribute( 'data-large_image_height' ), 10 ) || image.naturalHeight || image.height
		};
	}

	function getMagnifier( container, mobile ) {
		return container.querySelector( '.' + MAGNIFIER_CLASS + ( mobile ? '.' + MOBILE_CLASS : ':not(.' + MOBILE_CLASS + ')' ) );
	}

	function setMagnifierImage( magnifier, source ) {
		if ( ! magnifier || ! source ) {
			return;
		}

		magnifier.style.backgroundImage = 'url("' + source.url.replace( /"/g, '\\"' ) + '")';
		magnifier.dataset.width = source.width;
		magnifier.dataset.height = source.height;
	}

	function getContainerFromEventTarget( target ) {
		for ( const selector of SELECTORS ) {
			const container = target.closest( selector );

			if ( container ) {
				return container;
			}
		}

		return null;
	}

	function getPosition( event, container ) {
		const rect = container.getBoundingClientRect();

		return {
			x: Math.max( 0, Math.min( rect.width, event.clientX - rect.left ) ),
			y: Math.max( 0, Math.min( rect.height, event.clientY - rect.top ) ),
			width: rect.width,
			height: rect.height
		};
	}

	function updateMagnifier( event, container, magnifier ) {
		const image = getImageFromContainer( container );
		const source = getSourceImage( image );
		const position = getPosition( event, container );

		if ( ! source || ! position.width || ! position.height ) {
			return;
		}

		const magnifierSize = isMobile() ? 150 : 200;
		const sourceWidth = source.width || image.naturalWidth || position.width;
		const sourceHeight = source.height || image.naturalHeight || position.height;
		const scaleX = sourceWidth / position.width;
		const scaleY = sourceHeight / position.height;
		const backgroundX = ( position.x * scaleX ) - ( magnifierSize * 0.4 );
		const backgroundY = ( position.y * scaleY ) - ( magnifierSize * 0.4 );

		magnifier.style.width = magnifierSize + 'px';
		magnifier.style.height = magnifierSize + 'px';
		magnifier.style.left = position.x + 'px';
		magnifier.style.top = position.y + 'px';
		magnifier.style.backgroundSize = sourceWidth + 'px ' + sourceHeight + 'px';
		magnifier.style.backgroundPosition = ( backgroundX * -1 ) + 'px ' + ( backgroundY * -1 ) + 'px';
	}

	function showMagnifier( container, mobile ) {
		const magnifier = getMagnifier( container, mobile );

		if ( magnifier ) {
			magnifier.classList.add( 'is-visible' );
		}
	}

	function hideMagnifier( container, mobile ) {
		const magnifier = getMagnifier( container, mobile );

		if ( magnifier ) {
			magnifier.classList.remove( 'is-visible' );
		}
	}

	function decorateClassicContainer( container ) {
		const image = getImageFromContainer( container );

		if ( ! image ) {
			return;
		}

		const source = getSourceImage( image );
		const desktop = getMagnifier( container, false );
		const mobile = getMagnifier( container, true );

		if ( source ) {
			setMagnifierImage( desktop, source );
			setMagnifierImage( mobile, source );
		}
	}

	function decorateBlockContainer( container ) {
		const image = getImageFromContainer( container );

		if ( ! image || container.querySelector( '.' + MAGNIFIER_CLASS ) ) {
			return;
		}

		// Do not compete with WooCommerce's own hover-zoom mode.
		if ( image.classList.contains( 'wc-block-woocommerce-product-gallery-large-image__image--hoverZoom' ) ) {
			return;
		}

		const source = getSourceImage( image );

		if ( ! source ) {
			return;
		}

		const desktop = document.createElement( 'span' );
		desktop.className = MAGNIFIER_CLASS;
		desktop.setAttribute( 'aria-hidden', 'true' );

		const mobile = document.createElement( 'span' );
		mobile.className = MAGNIFIER_CLASS + ' ' + MOBILE_CLASS;
		mobile.setAttribute( 'aria-hidden', 'true' );

		setMagnifierImage( desktop, source );
		setMagnifierImage( mobile, source );

		container.appendChild( desktop );
		container.appendChild( mobile );
	}

	function decorateAll() {
		document.querySelectorAll( '.woocommerce-product-gallery__image' ).forEach( decorateClassicContainer );
		document.querySelectorAll( '.wc-block-product-gallery-large-image__wrapper' ).forEach( decorateBlockContainer );
	}

	document.addEventListener( 'mousemove', function ( event ) {
		if ( isMobile() ) {
			return;
		}

		const container = getContainerFromEventTarget( event.target );

		if ( ! container ) {
			return;
		}

		const magnifier = getMagnifier( container, false );

		if ( ! magnifier ) {
			return;
		}

		showMagnifier( container, false );
		updateMagnifier( event, container, magnifier );
	} );

	document.addEventListener( 'mouseleave', function ( event ) {
		const container = getContainerFromEventTarget( event.target );

		if ( container ) {
			hideMagnifier( container, false );
		}
	}, true );

	document.addEventListener( 'touchstart', function ( event ) {
		const container = getContainerFromEventTarget( event.target );

		if ( ! container || ! isMobile() ) {
			return;
		}

		const magnifier = getMagnifier( container, true );

		if ( ! magnifier ) {
			return;
		}

		showMagnifier( container, true );
		updateMagnifier( event.touches[ 0 ], container, magnifier );
	}, { passive: true } );

	document.addEventListener( 'touchmove', function ( event ) {
		const container = getContainerFromEventTarget( event.target );

		if ( ! container || ! isMobile() ) {
			return;
		}

		const magnifier = getMagnifier( container, true );

		if ( ! magnifier ) {
			return;
		}

		updateMagnifier( event.touches[ 0 ], container, magnifier );
	}, { passive: true } );

	document.addEventListener( 'touchend', function ( event ) {
		const container = getContainerFromEventTarget( event.target );

		if ( container ) {
			hideMagnifier( container, true );
		}
	}, { passive: true } );

	document.addEventListener( 'DOMContentLoaded', decorateAll );

	const observer = new MutationObserver( function () {
		decorateAll();
	} );

	observer.observe( document.documentElement, {
		childList: true,
		subtree: true
	} );
}() );
