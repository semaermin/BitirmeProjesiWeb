import React, { useState, useRef, useEffect } from 'react';
import '../assets/styles/components/accordion.scss';
import { CaretDownFill } from 'react-bootstrap-icons';
import { useTheme } from '../context/ThemeContext';

const dataCollection = [
  {
    question: 'Sermify Nedir, ne işe yarar?',
    answer:
      'Sermify İngilizce öğrenmek isteyen kişilerin 10-15 saniyelik kısa videolar ve testler ile İngilizce pratiği yapmasını ve gelişimi destekleyen bir İngilizce öğrenme platformudur.',
  },
  {
    question: "Sermify'da ne tür içerikler bulunabilir?",
    answer:
      "Sermify'da 10-15 saniyelik videolar ve İngilizce testler bulunur. Bu videolar ve testler çeşitli konularda İngilizce pratiği yapmak için tasarlanmıştır.",
  },
  {
    question: 'Sermify nasıl kullanılır?',
    answer:
      "Sermify'ı kullanmak oldukça basittir. İlk olarak, bir hesap oluşturmanız gerekmektedir. Ardından, platformdaki videoları izleyebilir ve testleri çözebilirsiniz. Her test sonunda, ilerlemenizi takip edebilir ve güçlü ve zayıf yönlerinizi görebilirsiniz.",
  },
  {
    question: "Sermify'da hangi seviyede İngilizce öğrenebilirim?",
    answer:
      'Sermify, tüm seviyelerdeki İngilizce öğreniciler için uygundur. Başlangıç seviyesinden ileri seviyeye kadar olan kullanıcılar faydalanabilir.',
  },
  {
    question: "Sermify'daki videoların içeriği nedir?",
    answer:
      "Sermify'daki videolar çeşitli konularda kısa konuşmalar, diyaloglar, günlük yaşam sahneleri ve pratik İngilizce kullanımına odaklanmıştır. Bu videolar, kullanıcıların dil becerilerini geliştirmelerine yardımcı olacak şekilde tasarlanmıştır.",
  },
  {
    question: "Sermify'da bulunan testler ne tür sorular içerir?",
    answer:
      "Sermify'daki testler, kelime bilgisi, dilbilgisi ve okuma anlama becerilerini ölçen çeşitli sorular içerir. Bu testler, kullanıcıların İngilizce becerilerini çeşitli yönlerden değerlendirmeye yardımcı olur.",
  },
];

function Accordion() {
  const [accordion, setActiveAccordion] = useState(-1);
  const { theme } = useTheme();
  const contentRefs = useRef([]);

  useEffect(() => {
    contentRefs.current.forEach((ref, index) => {
      if (ref) {
        ref.style.height =
          accordion === index ? `${ref.scrollHeight}px` : '0px';
        ref.style.opacity = accordion === index ? '1' : '0';
      }
    });
  }, [accordion]);

  function toggleAccordion(index) {
    if (index === accordion) {
      setActiveAccordion(-1);
      return;
    }
    setActiveAccordion(index);
  }

  return (
    <div id="accordion" className={theme}>
      <div className="accordion">
        {dataCollection.map((item, index) => (
          <div
            className="accordion-element"
            key={index}
            onClick={() => toggleAccordion(index)}
          >
            <div
              className={`accordion-heading ${
                accordion === index ? 'active' : 'inactive'
              }`}
            >
              <h3 className={accordion === index ? 'active' : ''}>
                {item.question}
              </h3>
              <div className="arrow">
                <span className={accordion === index ? 'active' : 'inactive'}>
                  <CaretDownFill></CaretDownFill>
                </span>
              </div>
            </div>
            <div
              ref={(el) => (contentRefs.current[index] = el)}
              className="accordion-bottom-text"
            >
              <p className={accordion === index ? 'active' : 'inactive'}>
                {item.answer}
              </p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default Accordion;
