import React, { forwardRef, useRef, useState, useEffect, useCallback, useImperativeHandle } from 'react';
import HTMLFlipBook from 'react-pageflip';

import CoverFront from '@assets/cards/books/FrontCover.webp';
import CoverBack from '@assets/cards/books/BackCover.webp';

const TOTAL_PAGES = 88;
const PATH_FILTERED = 'https://ik.imagekit.io/kyla08/foto-filter';
const PATH_NORMAL   = 'https://ik.imagekit.io/kyla08/foto-polos';
const IMG_VERSION = '202630'

const SEPIA_COLOR = '#f2e8d5';
const SIZE_FILTERED = '141% 115%';
const SIZE_NORMAL   = '101% 110%';

const CROP_WIDTH = '145%';
const CROP_HEIGHT = '115%';

const COVER_WIDTH = '210%';
const COVER_HEIGHT = '115%';

const MAX_ROTATION = 5;
const BUBBLE_COUNT = 12;

const FILTERED = false;

const Page = forwardRef((props, ref) => {
  const isRightPage = props.number % 2 !== 0;
  const currentBgSize = props.isFiltered ? SIZE_FILTERED : SIZE_NORMAL;

  return (
    <div className="page" ref={ref} data-density="soft">
      <div
        className="relative w-full h-full overflow-hidden"
        style={{
           backgroundColor: SEPIA_COLOR,
           border: 'none',
        }}
      >
        <div
          className="absolute inset-0 w-full h-full"
          style={{
            backgroundImage: `url(${props.contentImage})`,
            backgroundSize: currentBgSize,
            backgroundPosition: 'center center',
            backgroundRepeat: 'no-repeat',
            boxShadow: isRightPage
                ? 'inset 15px 0 30px -10px rgba(0,0,0,0.7)'
                : 'inset -15px 0 30px -10px rgba(0,0,0,0.7)'
          }}
        />

        <div
            className="absolute inset-0 pointer-events-none"
            style={{
              boxShadow: isRightPage
                ? 'inset -25px 0 30px -10px rgba(0,0,0,0.7)'
                : 'inset 25px 0 30px -10px rgba(0,0,0,0.7)'
            }}
        />

        <span className="absolute bottom-4 right-4 text-[10px] text-gray-500 font-sans opacity-70">
          {props.number}
        </span>
      </div>
    </div>
  );
});
Page.displayName = 'Page';

const Cover = forwardRef((props, ref) => {
  return (
    <div className="cover" ref={ref} data-density="hard">
      <div
        className="relative w-full h-full"
        style={{
          backgroundImage: `url(${props.bgImage})`,
          backgroundSize: `${COVER_WIDTH} ${COVER_HEIGHT}`,
          backgroundPosition: 'center',
          boxShadow: 'inset 0 0 15px rgba(0,0,0,0)',
          filter: `
                brightness(1.1)
                contrast(0.9)
                saturate(0.1)
                hue-rotate(20deg)
                sepia(0)
          `
        }}
      >
        <div className="absolute left-0 top-0 bottom-0 w-4 z-20 pointer-events-none" />
      </div>
    </div>
  );
});
Cover.displayName = 'Cover';

const AssistantBook = forwardRef(({
    onPageChange,
    width = 400,
    height = 600,
    initialFilterState = FILTERED
}, ref) => {

  const bookRef = useRef(null);
  const containerRef = useRef(null);
  const pages = Array.from({ length: TOTAL_PAGES }, (_, i) => i + 1);

  const [isMobile, setIsMobile] = useState(false);
  const [bubbles, setBubbles] = useState([]);

  // Changed from `clicked` to `isPressed`
  const [isPressed, setIsPressed] = useState(false);

  const [isFiltered, setIsFiltered] = useState(initialFilterState);
  useEffect(() => {
    setIsFiltered(initialFilterState);
  }, [initialFilterState]);

  useImperativeHandle(ref, () => ({
    flipPrev: () => bookRef.current.pageFlip().flipPrev(),
    flipNext: () => bookRef.current.pageFlip().flipNext(),
    flip: (index) => bookRef.current.pageFlip().flip(index),
    getTotalPages: () => TOTAL_PAGES + 2
  }));

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768);
    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const onFlip = useCallback((e) => {
    if (onPageChange) onPageChange(e.data);
  }, [onPageChange]);

  const handlePointerMove = (e) => {
    if (!containerRef.current) return;
    if (e.buttons === 1) return;

    const rect = containerRef.current.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const xPct = x / rect.width;
    const yPct = y / rect.height;
    const xRot = (yPct - 0.5) * 2;
    const yRot = (xPct - 0.5) * 2;

    containerRef.current.style.setProperty('--rx', `${-xRot * MAX_ROTATION}deg`);
    containerRef.current.style.setProperty('--ry', `${yRot * MAX_ROTATION}deg`);
    containerRef.current.style.setProperty('--mx', `${xPct * 100}%`);
    containerRef.current.style.setProperty('--my', `${yPct * 100}%`);
    containerRef.current.style.setProperty('--opacity', '1');
  };

  const handlePointerLeave = () => {
    setIsPressed(false); // Make sure it un-presses when moving cursor out

    if (!containerRef.current) return;
    containerRef.current.style.setProperty('--rx', '0deg');
    containerRef.current.style.setProperty('--ry', '0deg');
    containerRef.current.style.setProperty('--opacity', '0');
  };

  const handlePointerDown = () => {
    setIsPressed(true);
    spawnBubbles();
  };

  const handlePointerUp = () => {
    setIsPressed(false);
  };

  const spawnBubbles = useCallback(() => {
    const newBubbles = Array.from({ length: BUBBLE_COUNT }, (_, i) => {
      const edge = Math.floor(Math.random() * 4);
      let left, top;
      switch (edge) {
        case 0:
            left = Math.random() * 100;
            top = 0;
            break;
        case 1:
            left = 100;
            top = Math.random() * 100;
            break;
        case 2:
            left = Math.random() * 100;
            top = 100;
            break;
        case 3:
            left = 0;
            top = Math.random() * 100;
            break;
        default:
            left = 0;
            top = 0;
      }
      return {
        id: Date.now() + i,
        left, top,
        size: 8 + Math.random() * 12,
        duration: 1.5 + Math.random() * 2,
        driftX: (Math.random() - 0.5) * 120,
        driftY: -60 - Math.random() * 100,
      };
    });
    setBubbles((prev) => [...prev, ...newBubbles]);
  }, []);

  return (
    <div className="relative w-full h-full flex flex-col items-center justify-center">
      <style>{`
        .book-wrapper {
          --rx: 0deg;
          --ry: 0deg;
          --mx: 50%;
          --my: 50%;
          --opacity: 0;
          --scale: 1;
          perspective: 1500px;
        }
        .book-shadow {
            position: absolute;
            top: 5%; left: 2.5%; width: 95%; height: 95%; z-index: 0;
            background: rgba(0, 10, 30, 0.6);
            filter: blur(50px);
            border-radius: 30px;
            pointer-events: none;
            /* Added transition to match the book inner scale */
            transition: transform 0.2s cubic-bezier(0.1, 0.4, 0.3, 1), opacity 0.2s ease;
        }
        .book-inner {
          z-index: 10;
          transform: rotateX(var(--rx)) rotateY(var(--ry)) scale(var(--scale));
          transition: transform 0.2s cubic-bezier(0.1, 0.4, 0.3, 1);
          will-change: transform;
          transform-style: preserve-3d;
        }

        /* WET EFFECT LAYERS */
        .wet-overlay {
          opacity: 0.6;
          mix-blend-mode: hard-light;
          pointer-events: none;
          filter: url(#wet-glimmer) brightness(1.1) contrast(1.2);
        }
        .wet-glint {
           opacity: var(--opacity);
           transition: opacity 0.4s ease;
           mix-blend-mode: soft-light;
           pointer-events: none;
           background: radial-gradient(circle at calc(var(--mx)) calc(var(--my)), rgba(255, 255, 255, 0.6) 0%, transparent 60%);
        }

        .bubble {
          position: absolute; border-radius: 50%;
          background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(255,255,255,0.1) 60%, rgba(255,255,255,0.4) 100%);
          box-shadow: inset 0 0 4px rgba(255,255,255,0.6), 0 2px 5px rgba(0,0,0,0.1);
          animation: floatBubble var(--dur) ease-out forwards;
          pointer-events: none; z-index: 999;
        }
        @keyframes floatBubble {
          0% { transform: translate(0, 0) scale(0.5); opacity: 0; }
          20% { opacity: 1; transform: translate(0, 0) scale(1.1); }
          100% { transform: translate(var(--dx), var(--dy)) scale(0); opacity: 0; }
        }
      `}</style>

      <div
        className="relative"
        ref={containerRef}
        onPointerMove={handlePointerMove}
        onPointerLeave={handlePointerLeave}
        onPointerDown={handlePointerDown}
        onPointerUp={handlePointerUp}
        onPointerCancel={handlePointerUp}
      >
        <div className="book-wrapper relative">

          {/* 1. Shadow Layer */}
          <div
             className="book-shadow"
             style={{
                transform: isPressed ? 'scale(0.96)' : 'scale(1)',
                opacity: isPressed ? 0.6 : 0.8
             }}
          />

          {/* 2. Book Inner (The tilting part) */}
          <div
            className="book-inner relative"
            style={{ '--scale': isPressed ? 0.98 : 1 }}
          >
            <HTMLFlipBook
              width={width}
              height={height}
              size="fixed"
              usePortrait={isMobile}
              minWidth={200}
              maxWidth={1000}
              minHeight={300}
              maxHeight={1200}
              drawShadow={true}
              flippingTime={1000}
              useMouseEvents={true}
              showCover={true}
              mobileScrollSupport={true}
              ref={bookRef}
              onFlip={onFlip}
            >
              <Cover key="front-cover" bgImage={CoverFront} />
                {pages.map((pageNum) => {
                  const basePath = isFiltered ? PATH_FILTERED : PATH_NORMAL;
                  const fileName = isFiltered ? `${pageNum}.png` : `${pageNum}_dlor.png`;

                  return (
                    <Page
                      key={pageNum}
                      number={pageNum}
                      contentImage={`${basePath}/${fileName}?tr=w-400,q-80,f-auto,${IMG_VERSION}`}
                      isFiltered={isFiltered}
                    />
                  );
                })}
              <Cover key="back-cover" bgImage={CoverBack} />
            </HTMLFlipBook>
          </div>

          {/* 3. Bubbles Layer */}
          <div className="absolute inset-0 pointer-events-none z-1000">
            {bubbles.map((b) => (
              <div
                key={b.id}
                className="bubble"
                style={{
                  left: `${b.left}%`,
                  top: `${b.top}%`,
                  width: `${b.size}px`,
                  height: `${b.size}px`,
                  '--dur': `${b.duration}s`,
                  '--dx': `${b.driftX}px`,
                  '--dy': `${b.driftY}px`,
                }}
                onAnimationEnd={() =>
                  setBubbles((prev) => prev.filter((item) => item.id !== b.id))
                }
              />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
});

AssistantBook.displayName = 'AssistantBook';
export default AssistantBook;
