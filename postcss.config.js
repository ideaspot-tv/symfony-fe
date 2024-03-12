const purgecss = require('@fullhuman/postcss-purgecss')

module.exports = {
    plugins: [
        purgecss({
            content: ['./templates/**/*.twig'],
            safelist: {
                greedy: [/tooltip/]
            }
        })
    ]
}
