// .prettierrc.cjs
module.exports = {
    plugins: ["prettier-plugin-blade"],
    overrides: [
        {
            files: "*.blade.php",
            options: {
                parser: "blade",
                singleQuote: false,
                printWidth: 120,
                wrapAttributes: "preserve",
            },
        },
    ],
};
