    var charts = []; 
    
    body = document.querySelector("body");

    function resetColors(){
        generatedColors = [];
    }

    let generatedColors = [];

    function generateUniqueColor(generatedColor) {
        let color;

        do {
            // Divise les couleurs en plages larges (0, 85, 170, 255)
            let r = Math.floor(Math.random() * 4) * 85; // Les valeurs possibles pour R seront 0, 85, 170, 255
            let g = Math.floor(Math.random() * 4) * 85; // Les valeurs possibles pour G seront 0, 85, 170, 255
            let b = Math.floor(Math.random() * 4) * 85; // Les valeurs possibles pour B seront 0, 85, 170, 255
            
            // Crée une couleur hexadécimale à partir des valeurs RGB
            color = `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
            
        } while (generatedColor.includes(color)  || color === '#ffffff'); // Vérifie que la couleur n'a pas déjà été générée

        generatedColor.push(color); // Ajoute la couleur au tableau
        return color;
    }

    lastChart = ""
    function chart(type, label, data, myChart="chartArea", varBarColor= null, unit = '') {
        area = document.querySelector('#'+myChart)
    
        if (typeof generatedColors[myChart] === "undefined") {
            generatedColors[myChart] = [];
            barColors = [];
            for (let index = 0; index < label.length; index++) {
                barColors.push(generateUniqueColor(generatedColors[myChart]));
            }
        }else{
            barColors = generatedColors[myChart].slice(this.length - data.length)
        }
        
        if (varBarColor != null) barColors = generatedColors["chartArea"].slice(this.length - data.length)

        found = false;
        if (charts && area.querySelector('canvas') !== null) {
            for (let index = 0; index < charts.length; index++) {
                if (charts[index].canvas == area.querySelector('canvas')) {
                    found = true;
                    foundIndex = index;
                }         
            }
        }

        area.innerHTML = "<canvas id='"+myChart+"-canvas' aria-label='' role='img'></canvas>"
        const canvas = document.getElementById(myChart+'-canvas');
        chartJs =  new Chart(canvas,{
        type: type,
        data:  {
            labels: label,
            datasets:[
                {
                    tension: .1,
                    pointRadius: 5, 
                    pointHoverRadius: 10, 
                    label: "Matériels triés par types",
                    backgroundColor: barColors,
                    data : data,
                }
            ]
        },
        options:{
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                position: 'bottom', // Vous pouvez tester 'bottom', 'left', 'right' aussi
                labels: {
                    usePointStyle: true,
                    font: {
                    size: 10, // Ajustez la taille selon vos besoins
                    }
                }
                }
            },
            animation: {
                duration: 1000, // Duration of the animation in milliseconds
                easing: 'easeOutQuart', // Animation easing (other options: 'linear', 'easeInQuad', etc.)
                animateRotate: true, // Rotate animation
                // animateScale: true, 
            },
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                    ticks:{
                        callback: value => `${value} ${unit}`
                    }
                }
            },
        }
    });
    chartJs["type"] = type;
    if (found == false) charts.push(chartJs);
    else charts[foundIndex] = chartJs;
    addData()
    }
