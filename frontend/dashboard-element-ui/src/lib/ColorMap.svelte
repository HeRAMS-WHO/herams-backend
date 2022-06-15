<script lang="ts">
    const randomHexByte = () => (Math.floor(Math.random() * 255)).toString(16).padStart(2, "0");
    const randomColor = () => '#' + randomHexByte() + randomHexByte() + randomHexByte();

    export let map: { [id: string]: string} = {};

    export let keys: string[] = [];
    let colorCache: { [id: string]: string} = {};
    
    
    $: {
        keys.forEach(v => {
        if (map[v] === undefined) {
            map[v] = colorCache[v] ?? randomColor()
        }
    })
    Object.keys(map).filter(k => !keys.includes(k)).forEach(k => {
      colorCache[k] = map[k]
      delete map[k]
      map = map
    })
    }

 </script>
<svelte:options tag={null}/>
<div>
{#each Object.keys(map).sort() as key}
<label >
    <span>{key}</span>
    <input type="color" name=colorMap[{key}] bind:value={map[key]}>

</label>
{:else}
    Select at least one variable to continue
{/each}
</div>
<style>
    span {
        width: auto;
        display: inline-block;
        line-height: 50px;
    }
    input {
        width: 50px;
        display: inline-block;
        right: 0;
        position: absolute;
        height: 100%;
        

    }
    label {
        position: relative;
        display: block;
        /* border: 1px solid black; */
        text-align: left;
        height: 50px;
        margin-bottom: 5px;
    }

    div {
        column-count: auto;
        column-gap: 50px;
        /* column-width: 200px; */
        max-width: 500px;
        padding: 10px;
        /* background-color: rgba(255, 0, 0, 0.3); */
    }
</style>