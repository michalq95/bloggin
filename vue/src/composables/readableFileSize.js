export function useHumanReadableFileSize(bytes) {
    (val) => ["Bytes", "Kb", "Mb", "Gb", "Tb"][Math.floor(Math.log2(val) / 10)];
    const k = 1024;
    const dm = 2 < 0 ? 0 : 2;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
}
