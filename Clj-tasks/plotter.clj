(ns plotter)

(defn draw-line [prt from to color]
  (prt (str "...Чертим линию из " from " в " to " используя " color " цвет.")))

(defn calc-new-position [distance angle {x :x y :y}]
  (let [angle-rads (* angle (/ Math/PI 180))
        new-x (+ x (* distance (Math/cos angle-rads)))
        new-y (+ y (* distance (Math/sin angle-rads)))]
    {:x (Math/round new-x) :y (Math/round new-y)}))

(defn move [prt distance {:keys [position angle color carriage-state] :as state}]
  (let [new-position (calc-new-position distance angle position)]
    (when (= carriage-state :down)
      (draw-line prt position new-position color))
    (assoc state :position new-position)))

(defn turn [prt angle state]
  (prt (str "Поворачиваем на " angle " градусов"))
  (update state :angle #(mod (+ % angle) 360)))

(defn carriage-up [prt state]
  (prt "Поднимаем каретку")
  (assoc state :carriage-state :up))

(defn carriage-down [prt state]
  (prt "Опускаем каретку")
  (assoc state :carriage-state :down))

(defn set-color [prt color state]
  (prt (str "Устанавливаем " color " цвет линии."))
  (assoc state :color color))

(defn set-position [prt position state]
  (prt (str "Устанавливаем позицию каретки в " position))
  (assoc state :position position))

(defn draw-triangle [prt size state]
  (reduce
    (fn [s _]
      (-> s
          (move prt size)
          (turn prt 120)))
    (carriage-down prt state)
    (range 3))
  (carriage-up prt state))

(defn draw-square [prt size state]
  (reduce
    (fn [s _]
      (-> s
          (move prt size)
          (turn prt 90)))
    (carriage-down prt state)
    (range 4))
  (carriage-up prt state))

(defn initialize-plotter-state []
  {:position {:x 0 :y 0}
   :angle 0
   :color :black
   :carriage-state :up})

(def printer println)

(let [state (initialize-plotter-state)]
  (-> state
      (draw-triangle printer 100)
      (set-position printer {:x 10 :y 10})
      (set-color printer :red)
      (draw-square printer 80)))
