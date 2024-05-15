import '../assets/styles/components/video-box.scss';

export default function VideoBox() {
  return (
    <div className="video-container">
      <div className="video-box">
        <div className="video">Video</div>
        <div className="video-texts-container">
          <p className="video-text">
            Lorem ipsum dolor sit am et consectetur, adipisicing elit. Expedita
            magnam reiciendis minima voluptates dolore sint nemo at illum fugiat
            ipsam nesciunt necessitatibus dicta modi deleniti.
          </p>
          <div className="video-choices">
            <button>Seçenek 1</button>
            <button>Seçenek 2</button>
          </div>
        </div>
      </div>
    </div>
  );
}
