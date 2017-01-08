package yoshino.controllers.publicity;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import yoshino.services.ChannelService;

import java.util.Map;

/**
 * Created by Volio on 2017/1/8.
 */
@RestController
@RequestMapping("/stream/callback")
public class CallbackController {

    private final ChannelService channelService;

    @Autowired
    public CallbackController(ChannelService channelService) {
        this.channelService = channelService;
    }

    /**
     * {"message":"streamStatus","updatedAt":"2017-01-09T00:20:45.17558025+08:00","data":{"id":"z1.lolibo.5c17c49dd87c866f9314ad42af55a2ed","url":"rtmp://pili-publish.lolihome.net/lolibo/5c17c49dd87c866f9314ad42af55a2ed?e=1483891993745\u0026token=h9luA0B9DoKvufzoct_-EJl0phs4j3lwhpugpCI8%3AyHfLLnlpwNm5fG1jPWZAcQtE53I%3D","status":"connected"}}
     *
     * @param map
     * @return
     */
    @PostMapping
    public Map streamCallback(@RequestBody Map map) {
        Map data = (Map) map.get("data");
        String streamId = (String) data.get("id");
        String status = (String) data.get("status");
        String streamKey = streamId.substring(streamId.lastIndexOf(".") + 1);
        channelService.updateStatus(streamKey, status);
        return map;
    }
}
