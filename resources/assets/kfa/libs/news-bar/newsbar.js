class NewsBar {
    constructor(e = {}) {
        this.container = document.querySelector(e.container ?? "#newsBar");
        this.track = document.querySelector(e.track ?? "#newsTrack");
        this.speed = e.speed ?? 100;
        this.startDelay = e.startDelay ?? 1e3;
        this.direction = e.direction ?? "rtl";
        this.pauseOnHover = e.pauseOnHover ?? !0;
        this.theme = e.theme ?? "light";
        this.sound = e.sound ?? !1;
        this.animation = e.animation ?? "scroll";
        this.itemSpace = void 0 !== e.itemSpace ? Number(e.itemSpace) : 0;
        this.position = 0;
        this.paused = !1;
        this.width = 0;
        this.animationFrame = null;
        this.events = {};
        this.startTime = 0; // For continuous animation timing
        this.offsetWhenResized = 0; // to keep track for resize

        // Error if not found
        if (!this.container || !this.track) {
            console.error("NewsBar: container or track not found");
            return;
        }

        window.addEventListener("load", () => {
            this.setup();
            setTimeout(() => this.start(), this.startDelay);
        });

        // Fix: On resize, update geometry and keep timing smooth
        window.addEventListener("resize", () => this.handleResize());

        if (this.pauseOnHover) {
            this.container.addEventListener("mouseenter", () => this.pause());
            this.container.addEventListener("mouseleave", () => this.resume());
        }

        void 0 !== document.hidden && document.addEventListener("visibilitychange", () => {
            document.hidden ? this.pause() : this.resume()
        });
    }

    setup() {
        cancelAnimationFrame(this.animationFrame);
        const originals = [...this.track.querySelectorAll("[data-original]")];
        if (originals.length === 0) {
            [...this.track.children].forEach(t => t.setAttribute("data-original", "true"))
        } else {
            this.track.innerHTML = "";
            originals.forEach(t => this.track.appendChild(t.cloneNode(!0)));
        }
        const html = this.track.innerHTML;
        this.track.innerHTML = html + html;
        this.width = this.track.scrollWidth / 2;
        // DO NOT reset this.position here!
        this.track.style.transform = `translateX(${this.position}px)`;
        document.documentElement.setAttribute("data-theme", this.theme);
    }

    start() {
        this.emit("start");
        // this.startTime = performance.now();
        this.startTime = performance.now() - this._timeElapsedForCurrentPosition();
        this.animate();
    }

    animate() {
        if (this.paused) return;
        const now = performance.now();
        // Maintain a consistent animation timeline even after resize/setup
        const elapsed = (now - this.startTime) / 1000;
        const sign = this.direction === "rtl" ? -1 : 1;
        this.position = (elapsed * this.speed * sign) % this.width;
        // Ensures value in [ -width, width )
        if (this.position < -this.width) this.position += this.width;
        if (this.position > this.width) this.position -= this.width;
        this.track.style.transform = `translateX(${this.position}px)`;
        this.animationFrame = requestAnimationFrame(() => this.animate());
    }

    pause() {
        if (this.paused) return;
        this.paused = !0;
        cancelAnimationFrame(this.animationFrame);
        this.container.classList.add("paused");
        this.emit("pause");
    }

    resume() {
        if (!this.paused) return;
        this.paused = !1;
        // Fix: resume time calculation for seamless animation
        this.startTime = performance.now() - this._timeElapsedForCurrentPosition();
        this.container.classList.remove("paused");
        this.animate();
        this.emit("resume");
    }

    setSpeed(e) { this.speed = e }

    setDirection(e) { ["rtl", "ltr"].includes(e) && (this.direction = e) }

    setTheme(e) { this.theme = e, document.documentElement.setAttribute("data-theme", e) }

    setItemSpace(e) { this.itemSpace = void 0 !== e ? Number(e) : 0, this.setup() }

    setItems(e) {
        this.track.innerHTML = e.map(t => `<span style="display:inline-block;${this.itemSpace ? 'margin-inline-end:' + this.itemSpace + "px;" : ""}white-space:pre;">${t}</span>`).join("");
        this.setup();
    }

    loadFrom(e) {
        fetch(e).then(t => t.json()).then(t => {
            t.items && (this.setItems(t.items), this.sound && new Audio("/assets/media/tick.mp3").play())
        })
    }

    on(e, t) {
        this.events[e] || (this.events[e] = []);
        this.events[e].push(t);
    }

    emit(e, t) {
        this.events[e] && this.events[e].forEach(s => s(t));
    }

    // ---- Fix: Handle resize smoothly without animation pausing! ----
    handleResize() {
        // Compute how much time had passed in terms of scroll before resize
        const elapsed = this._timeElapsedForCurrentPosition();
        // Call setup to update .width
        this.setup();
        // After setup, update startTime so the new animation resumes smoothly
        this.startTime = performance.now() - elapsed;
    }

    // Helper to figure out elapsed seconds that corresponds to the current position
    _timeElapsedForCurrentPosition() {
        if (!this.width) return 0;
        const sign = this.direction === "rtl" ? -1 : 1;
        // the animation is: position = elapsed * speed * sign % width
        // Solve for elapsed:
        // position = (elapsed * speed * sign) % width
        // => elapsed = ((position / (speed * sign)) + N * (width / (speed * sign))) for some N
        // We want the smallest positive equivalent
        let elapsed = this.position / (this.speed * sign);
        // Ensure positive
        if (elapsed < 0) elapsed += this.width / Math.abs(this.speed);
        return elapsed * 1000; // in ms (because it's subtracted from performance.now())
    }
}

window.NewsBar = NewsBar;