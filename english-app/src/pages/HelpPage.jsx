import Navbar from '../components/Navbar';
import Accordion from './../components/Accordion';
import { Helmet } from 'react-helmet-async';

function HelpPage() {
  return (
    <div>
      <Helmet>
        <meta
          name="description"
          content="Sermify Yardım Sayfası ile Sıkça Sorulan Sorular hakkında bilgi edinin ve Sermify kullanımı hakkında merak ettiklerinizi öğrenin."
        />
        <meta property="og:type" content="website" />
        <meta
          property="og:title"
          content="Sermify Yardım Sayfası ile karşılaştığınız sorunların çözümlerini öğrenebilirsiniz. "
        />
        <meta
          property="og:description"
          content="Sermify Yardım Sayfası ile Sıkça Sorulan Sorular hakkında bilgi edinin ve Sermify kullanımı hakkında merak ettiklerinizi öğrenin."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:url" content="https://www.sermify.com.tr/help" />
        <link rel="canonical" href="https://www.sermify.com.tr/help" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Yardım | Sermify</title>
      </Helmet>
      <Navbar item="help"></Navbar>
      <Accordion></Accordion>
    </div>
  );
}

export default HelpPage;
