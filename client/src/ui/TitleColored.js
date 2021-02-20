/**
 * Gives back a title where every character has different color
 * 
 * To optimize load time, i choose a nice looking precalculated render.
 * That is why this class is commented out
 * 
 * @param {*} props 
 */

const TitleColored = (props) => {

    // const title = props.title;

    // // https://flatuicolors.com/palette/ca
    // const colors = [
    //     "#ff9ff3",
    //     "#feca57",
    //     "#ff6b6b",
    //     "#48dbfb",
    //     "#1dd1a1",
    //     "#f368e0",
    //     "#ff9f43",
    //     "#ee5253",
    //     "#0abde3",
    //     "#10ac84",
    //     "#00d2d3",
    //     "#54a0ff",
    //     "#5f27cd",
    //     "#c8d6e5",
    //     "#2e86de"
    // ]

    // const getRandomColor = () => {
    //     var randomColor = colors[Math.floor(Math.random() * colors.length)];
    //     return randomColor;
    // }

    // let coloredTitle = [];
    // for (var i = 0; i < title.length; i++) {
    //     const char = title[i];
    //     const color = {
    //         color: getRandomColor()
    //     }
    //     coloredTitle.push(<span style={color}>{char}</span>)
    // }


    return (
        <h1 className="title"><span style={{color: "#feca57"}}>F</span><span style={{color: "#ee5253"}}>u</span><span style={{color: "#1dd1a1"}}>n</span><span style={{color: "#feca57"}}> </span><span style={{color: "#54a0ff"}}>A</span><span style={{color: "#2e86de"}}>r</span><span style={{color: "#10ac84"}}>i</span><span style={{color: "#c8d6e5"}}>t</span><span style={{color: "#5f27cd"}}>h</span><span style={{color:"#ff6b6b"}}>m</span><span style={{color: "#ff9ff3"}}>e</span><span style={{color: "#c8d6e5"}}>t</span><span style={{color: "#5f27cd"}}>i</span><span style={{color: "#ee5253"}}>c</span><span style={{color: "#ff9f43"}}>s</span></h1> 
        
        // <h1 className="title">
        //     {coloredTitle}        
        // </h1>
    )
}

export default TitleColored;

{/* 
Backup of nice looking title
<h1 class="title"><span style="color: rgb(254, 202, 87);">F</span><span style="color: rgb(238, 82, 83);">u</span><span style="color: rgb(29, 209, 161);">n</span><span style="color: rgb(254, 202, 87);"> </span><span style="color: rgb(84, 160, 255);">A</span><span style="color: rgb(46, 134, 222);">r</span><span style="color: rgb(16, 172, 132);">i</span><span style="color: rgb(200, 214, 229);">t</span><span style="color: rgb(95, 39, 205);">h</span><span style="color: rgb(255, 107, 107);">m</span><span style="color: rgb(255, 159, 243);">e</span><span style="color: rgb(200, 214, 229);">t</span><span style="color: rgb(95, 39, 205);">i</span><span style="color: rgb(238, 82, 83);">c</span><span style="color: rgb(255, 159, 67);">s</span></h1> 
*/}