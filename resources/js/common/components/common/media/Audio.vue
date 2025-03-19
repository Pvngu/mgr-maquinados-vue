<template>
    <a-row class="audio-player" justify="space-between" :wrap="false" >
        <a-col style="max-width: 800px; width: 100%;" class="controls">
            <!-- Play/Pause Button -->
            <a-button type="text" @click="togglePlayPause" size="large">
                <template #icon>
                    <PauseOutlined v-if="isPlaying" />
                    <CaretRightFilled v-else />
                </template>
            </a-button>
                <!-- Progress Bar -->
                <div class="progress-container" @click="setProgress">
                <div class="progress-bar" :style="{ width: progress + '%' }"></div>
            </div>
            <!-- Time Display -->
            <div class="time-display">
                <span>{{ formattedCurrentTime }}</span> /
                <span>{{ formattedDuration }}</span>
            </div>
        </a-col>
        <a-col class="controls" >
            <!-- Volume Control -->
            <div class="volume-control">
                <a-button @click="toggleMute" type="text">
                    <template #icon>
                        <Icon v-if="isMuted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m18 14l2-2m2-2l-2 2m0 0l-2-2m2 2l2 2" />
                                    <path d="M2 13.857v-3.714a2 2 0 0 1 2-2h2.9a1 1 0 0 0 .55-.165l6-3.956a1 1 0 0 1 1.55.835v14.286a1 1 0 0 1-1.55.835l-6-3.956a1 1 0 0 0-.55-.165H4a2 2 0 0 1-2-2Z" />
                                </g>
                            </svg>
                        </Icon>
                        <icon v-else>
                            <template #component>
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 14.959V9.04C2 8.466 2.448 8 3 8h3.586a.98.98 0 0 0 .707-.305l3-3.388c.63-.656 1.707-.191 1.707.736v13.914c0 .934-1.09 1.395-1.716.726l-2.99-3.369A.98.98 0 0 0 6.578 16H3c-.552 0-1-.466-1-1.041M16 8.5c1.333 1.778 1.333 5.222 0 7M19 5c3.988 3.808 4.012 10.217 0 14" />
                            </svg>
                            </template>
                        </icon>
                    </template>
                </a-button>
                <!-- <input type="range" min="0" max="1" step="0.01" v-model="volume" /> -->
            </div>
            <!-- Speed Control -->
            <div class="speed-control">
                <a-button
                    type="text"
                    @click="setPlaybackRate"
                >
                    {{ playbackRate }}x
                </a-button>
            </div>
            <!-- More Options -->
            <div>
                <slot name="extra"></slot>
            </div>
        </a-col>
    </a-row>
    <audio
        ref="audio"
        @timeupdate="updateProgress"
        @ended="resetPlayer"
        @loadedmetadata="updateDuration"
    >
        <source
            :src="src"
            type="audio/mp3"
        />
        Your browser does not support the audio element.
    </audio>
</template>

<script>
import { ref, watch, computed } from "vue";
import Icon, { CaretRightFilled, PauseOutlined } from "@ant-design/icons-vue";

export default {
    props: {
        src: {
            default: "",
        }
    },
    components: {
        CaretRightFilled,
        PauseOutlined,
        Icon
    },
    setup() {
        const isPlaying = ref(false);
        const isMuted = ref(false);
        const progress = ref(0);
        const volume = ref(1);
        const playbackRate = ref(1);
        const audio = ref(null);
        const duration = ref(0);
        const currentTime = ref(0);

        const formattedCurrentTime = computed(() =>
            audio.value ? formatTime(currentTime.value) : "00:00"
        );
        const formattedDuration = computed(() =>
            audio.value ? formatTime(duration.value) : "00:00"
        );

        const togglePlayPause = () => {
            if (audio.value.paused) {
                audio.value.play();
                isPlaying.value = true;
            } else {
                audio.value.pause();
                isPlaying.value = false;
            }
        };

        const updateProgress = () => {
            currentTime.value = audio.value.currentTime;
            progress.value =
                (audio.value.currentTime / audio.value.duration) * 100;
        };

        const setProgress = (event) => {
            const container = event.currentTarget;
            const rect = container.getBoundingClientRect();
            const clickX = event.clientX - rect.left;
            const width = container.offsetWidth;
            const newTime = (clickX / width) * audio.value.duration;

            audio.value.currentTime = newTime;
            currentTime.value = newTime;
        };

        const toggleMute = () => {
            audio.value.muted = !audio.value.muted;
            isMuted.value = audio.value.muted;
        };

        const setPlaybackRate = () => {
            const rate = playbackRate.value === 1 ? 2 : 1;
            audio.value.playbackRate = rate;
            playbackRate.value = rate;
        };

        const resetPlayer = () => {
            isPlaying.value = false;
            progress.value = 0;
            currentTime.value = 0;
        };

        const updateDuration = () => {
            duration.value = audio.value.duration;
        };

        // Format time in mm:ss
        const formatTime = (time) => {
            if (isNaN(time)) return "0:00";
            const minutes = Math.floor(time / 60);
            const seconds = Math.floor(time % 60);
            return `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
        };

        watch(volume, (newVolume) => {
            audio.value.volume = newVolume;
        });

        return {
            isPlaying,
            isMuted,
            progress,
            volume,
            playbackRate,
            audio,
            togglePlayPause,
            updateProgress,
            setProgress,
            toggleMute,
            setPlaybackRate,
            resetPlayer,
            formattedCurrentTime,
            formattedDuration,
            duration,
            updateDuration,
            currentTime,
        };
    },
};
</script>

<style scoped>
.audio-player {
    padding: 0.4rem;
    background: #f3f3f3;
    border-radius: 4px;
}

.controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress-container {
    flex: 1;
    height: 8px;
    background: #ddd;
    position: relative;
    cursor: pointer;
    border-radius: 4px;
}

.progress-bar {
    height: 100%;
    background: #007bff;
    border-radius: 4px;
    transition: width 0.1s ease;
}

.volume-control input[type="range"] {
    width: 100px;
}
</style>
