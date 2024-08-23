
export function useStyle() {
    const formattedText = (text) => text?.replace(/\n/g, '<br>');

    const circleStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: region.width ? `${region.width}px` : '',
        height: region.height ? `${region.height}px` : '',
        backgroundColor: region.fill || '',
        border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none',
        borderRadius: '50%',
        backgroundImage: `url(${region.patternSrc || ''})`,
        backgroundSize: 'cover'
    });

    const rectStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: `${region.width}px`,
        height: region.height ? `${region.height}px` : '',
        backgroundColor: region.fill || '',
        border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none',
        backgroundImage: `url(${region.patternSrc || ''})`,
        backgroundSize: 'cover',
        borderRadius: `${region.rx || 0}px ${region.ry || 0}px ${region.ry || 0}px ${region.rx || 0}px`
    });

    const textStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        fontSize: `${region.fontSize}px`,
        fontFamily: region.fontFamily,
        color: region.fill,
        fontWeight: region.fontWeight || 'normal',
        textStroke: region.stroke && region.strokeWidth ? `${region.strokeWidth}px ${region.stroke}` : '',
        WebkitTextStroke: region.stroke && region.strokeWidth ? `${region.strokeWidth}px ${region.stroke}` : '',
        textDecoration: region.underline ? 'underline' : ''
    });

    const ellipseStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: `${region.width}px`,
        height: region.height ? `${region.height}px` : '',
        backgroundColor: region.fill || '',
        border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none',
        borderRadius: `50% / ${(region.ry / region.rx) * 100}%`,
        backgroundImage: `url(${region.patternSrc || ''})`,
        backgroundSize: 'cover'
    });

    const triangleStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: 0,
        height: 0,
        backgroundColor: region.fill || '',
        borderLeft: `${region.width / 2}px solid transparent`,
        borderRight: `${region.width / 2}px solid transparent`,
        borderBottom: `${region.height}px solid ${region.fill}`,
        border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none'
    });

    const lineStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: `${region.width}px`,
        height: `${region.strokeWidth}px`,
        backgroundColor: region.fill || ''
    });

    const imageStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: `${region.width}px`,
        height: region.height ? `${region.height}px` : '',
        backgroundImage: `url(${region.src})`,
        backgroundSize: 'cover'
    });

    const videoStyle = (region) => ({
        position: 'absolute',
        left: `${region.left}px`,
        top: `${region.top}px`,
        width: `${region.width}px`,
        height: region.height ? `${region.height}px` : ''
    });

    return {
        videoStyle,
        imageStyle,
        lineStyle,
        triangleStyle,
        circleStyle,
        rectStyle,
        ellipseStyle,
        textStyle,
        formattedText,
    }
}