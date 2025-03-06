(ns guess-the-number)

(def ^:private range-start (atom nil))
(def ^:private range-end (atom nil))
(def ^:private last-guess (atom nil))

(defn start [start end]
  (reset! range-start start)
  (reset! range-end end)
  (reset! last-guess (quot (+ start end) 2))
  (str "Я готов! Моё первое предположение: " @last-guess))

(defn guess-my-number []
  (if (= @range-start @range-end)
    (str "Число найдено: " @range-start)
    @last-guess))

(defn smaller []
  (when (> @last-guess @range-start)
    (reset! range-end (dec @last-guess))
    (reset! last-guess (quot (+ @range-start @range-end) 2)))
  (guess-my-number))

(defn bigger []
  (when (< @last-guess @range-end)
    (reset! range-start (inc @last-guess))
    (reset! last-guess (quot (+ @range-start @range-end) 2)))
  (guess-my-number))