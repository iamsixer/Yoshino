package yoshino.controllers.publicity;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import yoshino.engine.StreamEngine;
import yoshino.errors.PageNotFoundException;
import yoshino.models.Channel;
import yoshino.services.ChannelService;

import java.util.Date;
import java.util.List;

/**
 * Created by Volio on 2016/12/15.
 */
@Controller
@RequestMapping(value = "/")
public class HomeController {

    private final ChannelService channelService;
    private final StreamEngine streamEngine;

    @Autowired
    public HomeController(ChannelService channelService, StreamEngine streamEngine) {
        this.channelService = channelService;
        this.streamEngine = streamEngine;
    }

    @GetMapping
    public String Home(Model model) {
        List<Channel> channels = channelService.getLivingChannels();
        for (Channel channel : channels) {
            channel.setStreamKey(streamEngine.getSnapshotPlayUrl(channel.getStreamKey()));
        }
        model.addAttribute("channels", channels);
        return "publicity/home";
    }

    @GetMapping("/{id}")
    public String getChannel(@PathVariable("id") Long id, Model model) {
        Channel channel = channelService.findOne(id);
        if (channel == null) {
            throw new PageNotFoundException();
        }
        model.addAttribute("channel", channel);
        model.addAttribute("user", channel.getUser());
        return "publicity/channel";
    }
}
