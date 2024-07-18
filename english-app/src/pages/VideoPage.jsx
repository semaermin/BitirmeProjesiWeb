// Third Party Imports
import { Helmet } from 'react-helmet-async';

import Navbar from '../components/Navbar';
import VideoBox from '../components/VideoBox';

function VideoPage() {
  return (
    <div>
      <Helmet>
        <meta
          name="description"
          content="Sermify'daki 10-15 saniyelik kısa videolar sayesinde İngilizcenizi geliştirin."
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Video" />
        <meta
          property="og:description"
          content="Sermify'daki 10-15 saniyelik kısa videolar sayesinde İngilizcenizi geliştirin."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Video | Sermify</title>
      </Helmet>

      <Navbar item="video"></Navbar>
      <VideoBox></VideoBox>
    </div>
  );
}

export default VideoPage;
