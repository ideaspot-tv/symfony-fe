import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["source"];
    static classes = ["supported"]

    connect() {
        console.log("sourceTarget:", this.sourceTarget);
        console.log("sourceTargets:", this.sourceTargets);
        console.log("hasSourceTarget:", this.hasSourceTarget);

        if ("clipboard" in navigator) {
            this.element.classList.add(this.supportedClass);
        }
    }

    copy(event) {
        event.preventDefault();
        navigator.clipboard.writeText(this.sourceTarget.value);
    }
}
