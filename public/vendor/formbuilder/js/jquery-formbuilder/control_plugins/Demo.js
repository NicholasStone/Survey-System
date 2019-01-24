if (!window.fbControls) window.fbControls = [];
window.fbControls.push(function (Control) {
    class Demo extends Control {
        build() {
            let options = [];
            for (let i = 0; i < 5; i++) {
                options.push(this.markup('options', null, null));
            }
            this.dom = this.markup('select', options, null);
            return this.dom;
        }

        onRender() {

        }
    }

    Control.register('Demo', Demo);
    return Demo;
});