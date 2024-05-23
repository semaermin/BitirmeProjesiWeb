import '../assets/styles/components/video-box.scss';
import { useTheme } from '../context/ThemeContext';

export default function VideoBox() {
  const { theme } = useTheme();

  return (
    <div className={theme}>
      <div className="video-container">
        <div className="video-box">
          <div className="video">Video</div>
          <div className="video-texts-container">
            <p className="video-text">
              Lorem ipsum dolor sit am et consectetur, adipisicing elit.
              Expedita magnam reiciendis minima voluptates dolore sint nemo at
              illum fugiat ipsam nesciunt necessitatibus dicta modi deleniti.
            </p>
            <div className="video-choices">
              <button>
                Lorem ipsum dolor sit am et consectetur, adipisicing elit.
                Expedita magnam reiciendis minima voluptates dolore sint nemo at
                illum
              </button>
              <button>
                Lorem ipsum dolor sit am et consectetur, adipisicing elit.
                Expedita magnam reiciendis minima voluptates dolore sint nemo at
                illum
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
