import Navbar from '../components/Navbar';
import Leaderboard from '../components/Leaderboard';
import { Helmet } from 'react-helmet-async';

function LeaderboardPage() {
  return (
    <div>
      <Helmet>
        <meta
          name="description"
          content="Puan tablosunda rakiplerin ile yarış, kendini test et!"
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Puan Tablosu" />
        <meta
          property="og:description"
          content="Puan tablosunda rakiplerin ile yarış, kendini test et!"
        />
        <meta property="og:locale" content="tr_TR" />
        <meta
          property="og:url"
          content="https://www.sermify.com.tr/leaderboard"
        />
        <link rel="canonical" href="https://www.sermify.com.tr/leaderboard" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Puan Tablosu | Sermify</title>
      </Helmet>
      <Navbar item="leaderboard"></Navbar>
      <Leaderboard></Leaderboard>
    </div>
  );
}

export default LeaderboardPage;
