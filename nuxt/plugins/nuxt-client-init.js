export default async (ctx) => {
    await ctx.store.dispatch('setting/nuxtClientInit', ctx)
}